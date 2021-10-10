<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    /**
     * @return Visit[] Returns an array of Visit objects
     */
    public function findVisitsPerDayOfThisWeek($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("DATE_FORMAT(v.startTime, '%Y-%m-%d') = :val")
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
      * @return Visit[] Returns an array of Visit objects
      */
    public function findVisitsPerMonthOfThisYear($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("DATE_FORMAT(v.startTime, '%Y-%m') = :val")
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Visit[] Returns an array of Visit objects
    */
    public function findNewVisits($value)
    {
        return $this->createQueryBuilder('v')
              ->andWhere("DATE_FORMAT(v.startTime, '%Y-%m-%d') = :val")
              ->setParameter('val', $value)
              ->orderBy('v.id', 'ASC')
              ->getQuery()
              ->getResult()
          ;
    }

    /*
    public function findOneBySomeField($value): ?Visit
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
