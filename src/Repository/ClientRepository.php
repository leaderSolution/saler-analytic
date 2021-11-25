<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Search by  term
     *
     * @return Client[]
     */
    public function search(?string $term, $seller)
    {
        $qb = $this->createQueryBuilder('c');

        if ($term) {
            $qb ->andWhere('c.user = :user')
                ->andWhere('c.codeUniq LIKE :term OR c.designation LIKE :term')
                ->setParameter('term', '%'.$term.'%')
                ->setParameter('user', $seller);
        }

        return $qb->getQuery()->execute();
    }

    /**
     * @return Client[]
     */
    public function findAllEmailAlphabetical()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.email', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }

    /**
    * @return Client[] Returns an array of Client objects
    */
    public function findNewClients($value)
    {
        return $this->createQueryBuilder('c')
            //   ->andWhere("DATE_FORMAT(v.createdAt, '%Y-%m-%d') = :val")
            //   ->setParameter('val', $value)
              ->orderBy('v.id', 'ASC')
              ->getQuery()
              ->getResult()
          ;
    }

    /**
     * @return Client[] Returns an array of unvisited clients during a quarter
     */
    public function findUnVisitedClientsDuringQuarter($code)
    {
        
        return $this->createQueryBuilder('c')
            ->andWhere('c.codeUniq <> :val')
            ->andWhere('c.isProspect = 0')
            ->setParameter('val', $code)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
