<?php

namespace App\Repository;

use App\Entity\TransactionSubcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionSubcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionSubcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionSubcategory[]    findAll()
 * @method TransactionSubcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionSubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionSubcategory::class);
    }

    // /**
    //  * @return TransactionSubcategory[] Returns an array of TransactionSubcategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransactionSubcategory
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
