<?php

namespace App\Repository;

use App\Entity\FootballTam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FootballTam|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballTam|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballTam[]    findAll()
 * @method FootballTam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballTamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FootballTam::class);
    }

//    /**
//     * @return FootballTam[] Returns an array of FootballTam objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FootballTam
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
