<?php 

namespace App\Helpers;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class Helper extends Controller {

	// check authentication
    public function authenticate($token) {
            $user = $this->getDoctrine()->getRepository('App:Users')->findOneBy(array('token' => $token));
            if($user){
                if(strtotime($user->getTokenExpire()->format('Y-m-d h:i:s')) < time()){
                    return true;
                } else {
                    return json_encode(['error' => 'Token expired.']);
                }
            }
            return json_encode(['error' => 'You are not authorized']);
    }
}