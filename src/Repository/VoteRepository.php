<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function results() {

        return $this->createQueryBuilder('v')
            ->select('SUM(v.kohana) as kohana')            
            ->addSelect('SUM(v.symfony) as symfony')            
            ->addSelect('SUM(v.laravel) as laravel')                               
            ->getQuery()
            ->getResult()
        ;

    }

    public function resultsByDay() {

        return $this->createQueryBuilder('v')
            ->select('v.added as day')     
            ->addSelect('SUM(v.kohana) as kohana')            
            ->addSelect('SUM(v.symfony) as symfony')            
            ->addSelect('SUM(v.laravel) as laravel')               
            ->groupBy("v.added")         
            ->getQuery()
            ->getResult()
        ;

    }

    // /**
    //  * @return Vote[] Returns an array of Vote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vote
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
