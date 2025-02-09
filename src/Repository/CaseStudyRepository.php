<?php

namespace App\Repository;

use App\Common\PageData;
use App\Entity\CaseStudy;
use App\View\RecentCaseStudyView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CaseStudy>
 */
class CaseStudyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaseStudy::class);
    }

    public function listForSitemap(): \Generator
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT p.slug, p.updated_at FROM case_study p WHERE p.status = 1";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        
        while (($row = $result->fetchAssociative()) !== false) {
            yield $row;
        }
    }

    public function listForAdmin(?string $title, ?PageData $pageData = null): \Generator
    {
        $nbData = null;
        $wheres = [];
        $params = [];
        
        $conn = $this->getEntityManager()->getConnection();
        if ($title) {
            $params['title'] = '%' . $title . '%';
            $wheres[] = 'p.title ILIKE :title';
        }

        $where = count($wheres) > 0 ? ' WHERE ' . implode(' AND ', $wheres) : '';

        $selectQuery = "SELECT p.id, p.title, p.slug, p.client, p.published_at, p.status FROM case_study p {$where} ORDER BY p.id DESC";
        if (null !== $pageData) {
            $countQuery = "SELECT COUNT(*) FROM case_study p {$where}";
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

    public function findOneBySlug(string $slug): ?CaseStudy
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

    public function nbPublished(): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id) as c')
            ->where('p.status = :status')
            ->setParameter('status', 1);

        $nb = $qb->getQuery()->getSingleScalarResult();

        return intval($nb);
    }

    /**
     * get recent published
     *
     * @param integer $limit default 10
     * @param \DateTimeImmutable|null $lastPublishedAt
     * @param integer|null $lastId
     * @return array<RecentCaseStudyView>
     */
    public function recentPublished(int $limit = 10, ?\DateTimeImmutable $lastPublishedAt = null, ?int $lastId = null): array
    {
        $caseStudies = [];

        $selectQuery = "
            SELECT p.id, p.slug, p.title, p.published_at, h.relative_path, h.hash
            FROM case_study p
            INNER JOIN file_uploaded h ON p.image_id = h.id 
            WHERE ";

        if ($lastPublishedAt && $lastId) {
            $selectQuery .= " (p.published_at, p.id) < (:publishedAt, :id) AND";
        }
        $selectQuery .= " p.status = :status
            ORDER BY p.published_at DESC, p.id DESC
            LIMIT {$limit}
        ";

        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($selectQuery);
        if ($lastPublishedAt && $lastId) {
            $stmt->bindValue('publishedAt', $lastPublishedAt->format('Y-m-d'));
            $stmt->bindValue('id', $lastId);
        }
        $stmt->bindValue('status', 1);
        $selectResult = $stmt->executeQuery();

        while (($row = $selectResult->fetchAssociative()) !== false) {
            $caseStudies[] = new RecentCaseStudyView(
                (int) $row['id'],
                $row['slug'], 
                $row['title'], 
                new \DateTimeImmutable($row['published_at']),
                $row['relative_path'],
                $row['hash']
            );
        }
        
        return $caseStudies;
    }
    
    //    /**
    //     * @return CaseStudy[] Returns an array of CaseStudy objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CaseStudy
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
