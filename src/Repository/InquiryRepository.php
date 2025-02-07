<?php

namespace App\Repository;

use App\Common\PageData;
use App\Entity\Inquiry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inquiry>
 */
class InquiryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inquiry::class);
    }

    public function listForAdmin(?PageData $pageData = null): \Generator
    {
        $nbData = null;
        $conn = $this->getEntityManager()->getConnection();

        $selectQuery = "SELECT i.id, i.first_name, i.last_name, i.company_name, i.job_title, i.email, i.country, i.phone_number, i.message, i.from_page FROM inquiry i ORDER BY i.id DESC";
        if (null !== $pageData) {
            $countQuery = "SELECT COUNT(*) FROM inquiry";
            $offset = $pageData->getOffset();
            $selectQuery .= sprintf(' LIMIT %s OFFSET %s', $pageData->length, $offset);
            $stmt = $conn->prepare($countQuery);
            $result = $stmt->executeQuery();
            $nbData = $result->fetchOne();
        }

        $stmt = $conn->prepare($selectQuery);
        $selectResult = $stmt->executeQuery();
        while (($row = $selectResult->fetchAssociative()) !== false) {
            yield $row;
        }

        return $nbData;
    }
    
    //    /**
    //     * @return Inquiry[] Returns an array of Inquiry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Inquiry
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
