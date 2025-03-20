<?php

namespace App\Repository;

use App\Common\PageData;
use App\Entity\DownloadWhitepaperRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DownloadWhitepaperRequest>
 */
class DownloadWhitepaperRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DownloadWhitepaperRequest::class);
    }

    public function listForAdmin(?PageData $pageData = null): \Generator
    {
        $nbData = null;
        $conn = $this->getEntityManager()->getConnection();

        $selectQuery = "
            SELECT i.id, i.first_name, i.last_name, i.company_name, i.job_title, i.email, i.country, i.phone_number, i.message, i.downloaded, i.created_at, p.title 
            FROM download_whitepaper_request i 
            JOIN post p ON i.whitepaper_id = p.id
            ORDER BY i.id DESC
        ";
        if (null !== $pageData) {
            $countQuery = "SELECT COUNT(*) FROM download_whitepaper_request";
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

    public function findByEmailAndWhitepaperId(string $email, int $whitepaperId): ?DownloadWhitepaperRequest
    {
        return $this->createQueryBuilder('d')
            ->where('d.email = :email')
            ->andWhere('IDENTITY(d.whitepaper) = :wp_id')
            ->setParameter('email', trim($email))
            ->setParameter('wp_id', $whitepaperId)
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return DownloadWhitepaperRequest[] Returns an array of DownloadWhitepaperRequest objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DownloadWhitepaperRequest
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
