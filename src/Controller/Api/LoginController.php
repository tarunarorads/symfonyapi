<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class LoginController extends Controller
{

    
    /**
     * @Route("/api/login", name="api_login")
     */

    public function login(Request $request) {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $username = $request->get('username'); // get username
            $password = $request->get('password'); // get password

            // find user in the system
            $users = $this->getDoctrine()->getRepository('App:Users')->findOneBy(['username' => $username,'password' => $password]);
            if ($users) { 
                // set token for the user
                $tokenArray = $this->getDoctrine()->getRepository('App:Users')->userToken($users);
                return new Response(json_encode($tokenArray));
            }
            return new Response(json_encode(['error'=>'1', 'msg' => 'Either Username or Password is incorrect.']));

        } else {
                return new Response(json_encode(['error'=>'1', 'msg' => 'Please send request using post method.']));
        }

    }



}
