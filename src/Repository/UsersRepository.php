<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Users::class);
    }

 
    // set user token
    public function userToken($users)
    {
        $response = [];
        $response['user'] = $users->getUsername(); 
        $response['token'] = bin2hex(openssl_random_pseudo_bytes(8));
        $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $users->setToken($response['token']);
        $users->setTokenExpire(new \DateTime($tokenExpiration));
        $em = $this->getEntityManager();
        $em->flush();
        return $response;
    }


}
