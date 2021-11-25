<?php

namespace App\Repository;

use App\Entity\Leave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Leave|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leave|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leave[]    findAll()
 * @method Leave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leave::class);
    }

    public function findSellerLeavesPerMonthOfThisYear(mixed $value, $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere("DATE_FORMAT(l.startAt, '%Y-%m') = :val")
            ->andWhere('l.user = :idS')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findSellerLeavesOfTheMonth(mixed $value, $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere("DATE_FORMAT(l.startAt, '%Y-%m-%d') = :val")
            ->andWhere('l.user = :idS')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSellerLeavesByQuarter(mixed $start, mixed $end, $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere("DATE_FORMAT(l.startAt, '%Y-%m-%d') between :start and :end")
            ->andWhere('l.user = :idS')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('idS', $user)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findSellerLeavesPerDayOfThisWeek(mixed $value, $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere("DATE_FORMAT(l.startAt, '%Y-%m-%d') = :val")
            ->andWhere('l.user = :idS')
            ->setParameter('val', $value)
            ->setParameter('idS', $user)
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Leave[] Returns an array of Leave objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Leave
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
