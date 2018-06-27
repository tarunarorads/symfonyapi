<?php

namespace App\Repository;

use App\Entity\League;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method League|null find($id, $lockMode = null, $lockVersion = null)
 * @method League|null findOneBy(array $criteria, array $orderBy = null)
 * @method League[]    findAll()
 * @method League[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeagueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, League::class);
    }


    public function findOneByName($value): ?League
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneById($value): ?League
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getTeams($name)
    {
        $league = $this->createQueryBuilder('l')
            ->andWhere('l.name = :val')
            ->setParameter('val', $name)
            ->getQuery()
            ->getOneOrNullResult();
            if($league){
                $teamList = [];
                    foreach ($league->getFootballTeams() as $key => $team) {
                        $teamList[$key]['id'] = $team->getId();
                        $teamList[$key]['name'] = $team->getName();
                    }

                    return $teamList; 
            }

            return false;
        
    }


    public function delete($id){
        $league = $this->find($id);
        if(!empty($league)){
            $em = $this->getEntityManager();
            $em->remove($league);
            $em->flush();
            return true;
        }
        return false;
    }




}
