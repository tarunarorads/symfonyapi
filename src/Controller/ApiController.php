<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\League;
use App\Entity\Users;
use App\Entity\FootballTeam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiController extends Controller
{

    public function getEntMan()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Route("/api/login", name="api_login")
     */

    public function login(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $username = $request->get('username');
        $password = $request->get('password');
        $users = $this->getDoctrine()->getRepository('App:Users')->findOneBy(
            array(
                'username' => $username,
                'password' => $password
            )
        );

        if ($users) { //implement your own validation method against your db
            $arrRtn['user'] = 'Username'; //Just return the user name for reference
            $arrRtn['token'] = bin2hex(openssl_random_pseudo_bytes(8)); //generate a random token
            $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));//the expiration date will be in one hour from the current moment
            $users->setToken($arrRtn['token']);
            $users->setTokenExpire(new \DateTime($tokenExpiration));
            $em->flush();
            return new Response(json_encode($arrRtn));
        }
        return new Response(json_encode(['error' => 'Authentication failed']));
    }


    /**
     * @Route("/api/league", name="api_league")
     */
    public function getLeagueTeams(Request $request) {
        $tokenAuth = $request->headers->get('Authorization');
        $name = $request->get('name');
            if ($this->authenticate($tokenAuth)) {
                $league = $this->getDoctrine()
                        ->getRepository(League::class)
                        ->findOneByName($name);
            $teamList = [];
                if($league == true){
                    foreach ($league->getFootballTeams() as $key => $team) {
                        $teamList[$key]['id'] = $team->getId();
                        $teamList[$key]['name'] = $team->getName();
                    }
                    return new Response(json_encode($teamList));
                } else {
                    return new Response(json_encode(['error' => 'Team not found.']));
                }
            } elseif($this->authenticate($tokenAuth) == "token expired") {
                return new Response($this->token_expired());
            } else {
                return new Response($this->deny_access());
            }
    }


    /**
     * @Route("/api/create-team", name="api_create_team")
     */
    public function createTeam(Request $request) {
        $tokenAuth = $request->headers->get('Authorization');
        if ($this->authenticate($tokenAuth)) {
                $strip = $request->get('strip');
                $teamName = $request->get('name');
                $league = $this->getDoctrine()
                        ->getRepository(League::class)
                        ->findOneByName($strip);
                $team = $this->createFootball($league,$teamName);
                if($league && $team){
                    return new Response(json_encode(['success' => 'Team created successfully.']));
                }
            } elseif($this->authenticate($tokenAuth) == "token expired") {
                return new Response($this->token_expired());
            } else {
                return new Response($this->deny_access());
            }
    }


    /**
     * @Route("/api/update-team/{id}", name="api_update_team")
     */
    public function updateTeam(Request $request,$id) {
        $tokenAuth = $request->headers->get('Authorization');
        if ($this->authenticate($tokenAuth)) {
                $em = $this->getDoctrine()->getManager();
                $strip = $request->get('strip');
                $teamName = $request->get('name');

                $findTeam = $this->getDoctrine()
                        ->getRepository(FootballTeam::class)
                        ->find($id);

                $league = $this->getDoctrine()
                        ->getRepository(League::class)
                        ->findOneByName($strip);

                $findTeam->setStrip($league);
                $findTeam->setName($teamName);
                $em->flush();
                return new Response(json_encode(['success' => 'Team updated successfully.']));
            } elseif($this->authenticate($tokenAuth) == "token expired") {
                return new Response($this->token_expired());
            } else {
                return new Response($this->deny_access());
            }
    }



    /**
     * @Route("/api/delete-league", name="api_delete_league")
     */
    public function deleteLeague(Request $request) {
        $tokenAuth = $request->headers->get('Authorization');
        if ($this->authenticate($tokenAuth)) {
            $name = $request->get('name');
            $league = $this->getDoctrine()
                    ->getRepository(League::class)
                    ->findOneByName($name);
                    if($league){
                        $em = $this->getDoctrine()->getManager();
                        $em->remove($league);
                        $em->flush();
                        return new Response(json_encode(['success' => 'League deleted successfully.']));
                    } else {
                        return new Response(json_encode(['success' => 'League not found']));
                    }

            } elseif($this->authenticate($tokenAuth) == "token expired") {
                return new Response($this->token_expired());
            } else {
                return new Response($this->deny_access());
            }
    }



    // check authentication
    public function authenticate($token) {
            $user = $this->getDoctrine()->getRepository('App:Users')->findOneBy(array('token' => $token));
            if($user){
                if(strtotime($user->getTokenExpire()->format('Y-m-d h:i:s')) < time()){
                    return true;
                } else {
                    return "token expired";
                }
            }
            return false;
    }


    public function deny_access() {
       return json_encode(['error' => 'You are not authorized']);
   }

   public function token_expired() {
      return json_encode(['error' => 'Token expired.']);
  }


  protected function createFootball($league,$teamName)
  {
      $em = $this->getDoctrine()->getManager();
      $teamObj = new FootballTeam();
      $teamObj->setStrip($league);
      $teamObj->setName($teamName);
      $em->persist($teamObj);
      $em->flush();
      return $teamObj;
  }



}
