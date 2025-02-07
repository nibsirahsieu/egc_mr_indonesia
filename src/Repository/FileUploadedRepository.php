<?php

namespace App\Repository;

use App\Entity\FileUploaded;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FileUploaded>
 */
class FileUploadedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileUploaded::class);
    }

    public function clearUsedBy(int $usedById, int $purpose): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $updateQuery = "UPDATE file_uploaded f SET f.used_by = NULL WHERE f.used_by = :used_by AND f.purpose = :purpose";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindValue('used_by', $usedById);
        $stmt->bindValue('purpose', $purpose);
        $nUpdated = $stmt->executeStatement();

        return $nUpdated;
    }

    //    /**
    //     * @return FileUploaded[] Returns an array of FileUploaded objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FileUploaded
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
