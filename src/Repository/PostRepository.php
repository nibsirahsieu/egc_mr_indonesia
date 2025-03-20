<?php

namespace App\Repository;

use App\Common\IdName;
use App\Common\PageData;
use App\Entity\Post;
use App\SearchFilter\PostFilter;
use App\View\RecentPostView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function listForSitemap(): \Generator
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT p.slug, pt.slug AS category_slug, p.updated_at FROM post p JOIN post_type pt ON p.type_id = pt.id WHERE p.status = 1";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        
        while (($row = $result->fetchAssociative()) !== false) {
            yield $row;
        }
    }

    public function listForAdmin(PostFilter $filter, ?PageData $pageData = null): \Generator
    {
        $nbData = null;
        $conn = $this->getEntityManager()->getConnection();

        $wheres = [];
        $params = [];
        if ($filter->type) {
            $params['type'] = $filter->type;
            $wheres[] = 'p.type_id = :type';
        }
        if ($filter->title) {
            $params['title'] = '%' . $filter->title . '%';
            $wheres[] = 'p.title ILIKE :title';
        }
        if ($filter->status) {
            $params['status'] = $filter->status;
            $wheres[] = 'p.status = :status';
        }

        $where = count($wheres) > 0 ? ' WHERE ' . implode(' AND ', $wheres) : '';
        $selectQuery = "SELECT p.id, t.slug AS category_slug, t.name AS category_name, p.title, p.slug, p.author, p.published_at, p.status FROM post p JOIN post_type t ON p.type_id = t.id {$where} ORDER BY p.published_at DESC, p.id DESC";
        if (null !== $pageData) {
            $countQuery = "SELECT COUNT(*) FROM post p {$where}";
            $offset = $pageData->getOffset();
            $selectQuery .= sprintf(' LIMIT %s OFFSET %s', $pageData->length, $offset);
            $stmt = $conn->prepare($countQuery);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $result = $stmt->executeQuery();
            $nbData = $result->fetchOne();
        }

        $stmt = $conn->prepare($selectQuery);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $selectResult = $stmt->executeQuery();
        while (($row = $selectResult->fetchAssociative()) !== false) {
            yield $row;
        }

        return $nbData;
    }

    public function findOneBySlug(string $slug): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function slugExists(string $slug, ?int $excludeId): bool
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id) as c')
            ->where('p.slug = :slug')
            ->setParameter('slug', trim($slug));

        if ($excludeId) {
            $qb
                ->andWhere('p.id <> :excludeId')
                ->setParameter('excludeId', $excludeId);
        }
    
        $countSlug = $qb->getQuery()->getSingleScalarResult();

        return intval($countSlug) > 0;
    }

    public function nbPosts(?int $typeId = null): int
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.type', 'pt')
            ->select('count(p.id) as c')
            ->where('p.status = :status')
            ->setParameter('status', 1);

        if ($typeId) {
            $qb
                ->andWhere('pt.id = :typeId')
                ->setParameter('typeId', $typeId);
        }

        $nb = $qb->getQuery()->getSingleScalarResult();

        return intval($nb);
    }

    public function postTypes(): array
    {
        $result = [];
        $sql = "SELECT COUNT (*) AS cnt, t.id, t.slug, t.name FROM post p JOIN post_type t ON p.type_id = t.id  WHERE p.status = 1 GROUP BY t.id ORDER BY t.name";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $selectResult = $stmt->executeQuery();
        while (($row = $selectResult->fetchAssociative()) !== false) {
            $result[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'slug' => $row['slug'],
                'count' => (int) $row['cnt']
            ];
        }

        return $result;
    }

    /**
     * get recent published posts with keyset pagination
     *
     * @param integer $limit
     * @param integer|null $typeId
     * @param \DateTimeImmutable|null $lastPublishedAt
     * @param integer|null $lastId
     * @return iterable
     */
    public function recentPosts(int $limit = 10, ?int $typeId = null, ?\DateTimeImmutable $lastPublishedAt = null, ?int $lastId = null): iterable
    {
        $params = [];
        $wheres = [];

        if ($typeId) {
            $params['type_id'] = $typeId;
            $wheres[] = "p.type_id = :type_id";
        }
        if ($lastPublishedAt && $lastId) {
            $params['publishedAt'] = $lastPublishedAt->format('Y-m-d');
            $params['id'] = $lastId;
            $wheres[] = "(p.published_at, p.id) < (:publishedAt, :id)";
        }
        $params['status'] = 1;
        $wheres[] = "p.status = :status";

        $where = count($wheres) > 0 ? ' WHERE ' . implode(' AND ', $wheres) : '';

        $selectQuery = "
            SELECT p.id, p.slug, p.title, p.published_at, h.relative_path AS h_relative_path, h.hash AS h_hash, 
            pt.id AS post_type_id, pt.slug AS post_type_slug, t.relative_path AS t_relative_path, t.hash AS t_hash
            FROM post p
            INNER JOIN file_uploaded h ON p.header_image_id = h.id 
            LEFT JOIN file_uploaded t ON p.thumbnail_id = t.id 
            LEFT JOIN post_type pt ON p.type_id = pt.id
            {$where}
            ORDER BY p.published_at DESC, p.id DESC
            LIMIT {$limit}
        ";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($selectQuery);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $selectResult = $stmt->executeQuery();
        while (($row = $selectResult->fetchAssociative()) !== false) {
            yield new RecentPostView(
                (int) $row['id'],
                $row['slug'], 
                $row['title'], 
                IdName::create($row['post_type_id'], $row['post_type_slug']), 
                new \DateTimeImmutable($row['published_at']),
                $row['h_relative_path'],
                $row['h_hash'],
                $row['t_relative_path'],
                $row['t_hash']
            );
        }
    }

    public function otherPosts(int $limit, int $excludeId, ?int $typeId): iterable
    {
        $where = "WHERE p.status = :status AND p.id <> :exclude_id";
        if ($typeId) {
            $where .= " AND p.type_id = :type_id";
        }
        $selectQuery = "
            SELECT p.id, p.slug, p.title, p.published_at, h.relative_path AS h_relative_path, h.hash AS h_hash, 
            pt.id AS post_type_id, pt.slug AS post_type_slug, t.relative_path AS t_relative_path, t.hash AS t_hash
            FROM post p
            INNER JOIN file_uploaded h ON p.header_image_id = h.id 
            LEFT JOIN file_uploaded t ON p.thumbnail_id = t.id 
            LEFT JOIN post_type pt ON p.type_id = pt.id
            {$where}
            ORDER BY p.published_at DESC, p.id DESC
            LIMIT {$limit}";
        
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($selectQuery);
        $stmt->bindValue('status', 1);
        if ($typeId) {
            $stmt->bindValue('type_id', $typeId);
        }
        $stmt->bindValue('exclude_id', $excludeId);

        $selectResult = $stmt->executeQuery();
        while (($row = $selectResult->fetchAssociative()) !== false) {
            yield new RecentPostView(
                (int) $row['id'],
                $row['slug'], 
                $row['title'], 
                IdName::create($row['post_type_id'], $row['post_type_slug']), 
                new \DateTimeImmutable($row['published_at']),
                $row['h_relative_path'],
                $row['h_hash'],
                $row['t_relative_path'],
                $row['t_hash']
            );
        }
    }

    public function featuredWhitepaper(string $slug): array
    {
        $selectQuery = "
            SELECT p.id, p.slug, p.title, h.relative_path AS h_relative_path, h.hash AS h_hash, t.relative_path AS t_relative_path, t.hash AS t_hash
            FROM post p
            INNER JOIN file_uploaded h ON p.header_image_id = h.id 
            LEFT JOIN file_uploaded t ON p.thumbnail_id = t.id 
            WHERE p.slug = :slug AND p.status = :status";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($selectQuery);
        $stmt->bindValue('status', 1);
        $stmt->bindValue('slug', $slug);

        $result = $stmt->executeQuery();
        
        return $result->fetchAssociative() ?: [];
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
