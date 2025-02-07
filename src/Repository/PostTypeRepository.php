<?php

namespace App\Repository;

use App\Entity\PostType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostType>
 */
class PostTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostType::class);
    }

    /**
     * get array key value for symfony ChoiceType
     *
     * @return array<string, int>
     */
    public function forChoiceType(): array
    {
        $choices = [];
        $postTypes = $this->findAllOrderedByName();
        foreach ($postTypes as $postType) {
            $choices[$postType->getName()] = $postType->getId();
        }

        return $choices;
    }

    /**
     *
     * @return PostType[] Returns an array of PostType objects
     */
    public function findAllOrderedByName(): array
    {
        return $this
            ->createQueryBuilder('p')
            ->andWhere('p.deletedAt IS NULL')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return PostType[] Returns an array of PostType objects
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

    //    public function findOneBySomeField($value): ?PostType
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
