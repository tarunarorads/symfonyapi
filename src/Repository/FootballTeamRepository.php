<?php

namespace App\Repository;

use App\Entity\FootballTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FootballTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballTeam[]    findAll()
 * @method FootballTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballTeamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FootballTeam::class);
    }


    public function create($name,$league)
    {
        $team = new FootballTeam();
        $this->createTeam($team,$name,$league);  
        return true;
    }

    public function createTeam($object,$name,$league)
    {
            $em = $this->getEntityManager();
            $object->setStrip($league);
            $object->setName($name);
            $em->persist($object);
            $em->flush();
            return true;
        
    }
 
    public function update($id,$name,$league)
    {
        $team = $this->find($id);
        if(!empty($team)){
            $this->createTeam($team,$name,$league);    
            return true;
        } 
            return false;
        
    }
}
