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
    public function findVisitsPerDayOfThisWeek($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') = :val")
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
    public function findVisitsPerMonthOfThisYear($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM') = :val")
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Visit[] Returns an array of Visit objects
    */
    public function findNewVisits($value): array
    {
        return $this->createQueryBuilder('v')
              ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') = :val")
              ->setParameter('val', $value)
              ->orderBy('v.id', 'ASC')
              ->getQuery()
              ->getResult()
          ;
    }

    /**
     * @return Visit[] Returns an array of Visit objects
     */
    public function findSellerVisitsByClient($sellerID): array
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v) as nbVisits, IDENTITY(v.client) as idC')
            ->andWhere("v.user = :idS")
            ->setParameter('idS', $sellerID)
            ->groupBy('v.client')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Visit[] Returns an array of Visit objects
     */
    public function findSellerNewVisits($value, $seller): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') = :val")
            ->andWhere("v.user = :idC")
            ->setParameter('val', $value)
            ->setParameter('idC', $seller)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Visit[] Returns an array of Visit objects
     */
    public function findSellerComingUpVisits($value, $seller): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') > :val")
            ->andWhere("v.user = :idC")
            ->setParameter('val', $value)
            ->setParameter('idC', $seller)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Visit[] Returns an array of Visit objects
     */
    public function findSellerDoneVisits($seller): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere("v.isDone = 1")
            ->andWhere("v.user = :idC")
            ->setParameter('idC', $seller)
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
    public function findSellerVisitsPerDayOfThisWeek(mixed $value, $user)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') = :val")
            ->andWhere('v.user = :idS')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('v.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSellerVisitsPerMonthOfThisYear(mixed $value, $user)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM') = :val")
            ->andWhere('v.user = :idS')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSellerVisitsOfTheMonth(mixed $value, $user)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') = :val")
            ->andWhere('v.user = :idS')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSellerUnvisitedClient(mixed $value, $user)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("DATE_FORMAT(v.startTime, '%Y-%m-%d %H:%i') <= :val")
            ->andWhere('v.user = :idS')
            ->andWhere('v.isDone = 0')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSellerVisitsByQuarter(mixed $start, mixed $end, $user)
    {
        return $this->createQueryBuilder('v')
            ->andWhere("TO_DATE(v.startTime, 'YYYY-MM-DD') between :start and :end")
            ->andWhere('v.user = :idS')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('idS', $user)
            ->orderBy('v.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
