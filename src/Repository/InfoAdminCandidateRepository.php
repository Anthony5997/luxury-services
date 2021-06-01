<?php

namespace App\Repository;

use App\Entity\InfoAdminCandidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfoAdminCandidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoAdminCandidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoAdminCandidate[]    findAll()
 * @method InfoAdminCandidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoAdminCandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoAdminCandidate::class);
    }

    // /**
    //  * @return InfoAdminCandidate[] Returns an array of InfoAdminCandidate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfoAdminCandidate
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
