<?php

namespace App\Repository;

use App\Common\PageData;
use App\Entity\RedirectUrl;
use App\SearchFilter\RedirectUrlFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RedirectUrl>
 */
class RedirectUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedirectUrl::class);
    }

    public function listForAdmin(?PageData $pageData = null, ?RedirectUrlFilter $filter = null): \Generator
    {
        $nbData = null;
        $conn = $this->getEntityManager()->getConnection();

        $params = [];
        $wheres = [];
        if ($filter) {
            if ($filter->oldUrl) {
                $params['old_url'] = '%' . $filter->oldUrl . '%';
                $wheres[] = 'i.old_url ILIKE :old_url';
            }

            if ($filter->newUrl) {
                $params['new_url'] = '%' . $filter->newUrl . '%';
                $wheres[] = 'i.old_url ILIKE :new_url';
            }
        }

        $where = count($wheres) > 0 ? ' WHERE ' . implode(' AND ', $wheres) : '';
        $selectQuery = "SELECT i.id, i.old_url, i.new_url FROM redirect_url i {$where} ORDER BY i.id DESC";
        
        if (null !== $pageData) {
            $countQuery = "SELECT COUNT(*) FROM redirect_url i {$where}";
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
    
    public function getRedirectedUrl(string $fromUrl): ?string
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.newUrl')
            ->andWhere('r.oldUrl = :url')
            ->setParameter('url', $fromUrl)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['newUrl'] : null;
    }

    //    /**
    //     * @return RedirectUrl[] Returns an array of RedirectUrl objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RedirectUrl
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
