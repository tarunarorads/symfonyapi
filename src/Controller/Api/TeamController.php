<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\League;
use App\Entity\FootballTeam;


class TeamController extends Controller
{

    public function getRepo(){
        return $this->getDoctrine()->getRepository(FootballTeam::class);
    }

    /**
     * @Route("/api/create-team", name="api_create_team")
     */
    public function create(Request $request) {
        if ($request->isMethod('POST')) {
            //check token validation
            $tokenAuth = $request->headers->get('Authorization');
            $response = $this->get('my_helper')->authenticate($tokenAuth);
            if ($response === true) {
                    $strip = $request->get('strip'); // get league name from field
                    $teamName = $request->get('name'); // get team name from field
                    // find league by the name
                    $league = $this->getDoctrine()
                            ->getRepository(League::class)
                            ->findOneByName($strip);
                    // create team        
                    $team = $this->getRepo()->create($teamName,$league);
                    if($league && $team){
                        return new Response(json_encode(['success'=>'1', 'msg' => 'Team created successfully in the system.']));
                    }
                } else {
                return new Response($response);
            }
        } else {
            return new Response(json_encode(['error'=>'1', 'msg' => 'Please send request using post method.']));
        }
    }


   /**
     * @Route("/api/update-team/{id}", name="api_update_team")
     */
    public function update(Request $request,$id) {
        if ($request->isMethod('POST')) {
            //check token validation
            $tokenAuth = $request->headers->get('Authorization');
            $response = $this->get('my_helper')->authenticate($tokenAuth);
            if ($response === true) {
                    $strip = $request->get('strip'); // get league name from field
                    $teamName = $request->get('name'); // get team name from field
                     // find league by the name
                    $league = $this->getDoctrine()
                            ->getRepository(League::class)
                            ->findOneByName($strip);
                    // update team        
                    $updateTeam = $this->getRepo()->update($id,$teamName,$league);
                    return new Response(json_encode(['success'=>'1', 'msg' => 'Team information updated successfully in the system.']));
                
                } else {
                return new Response($response);
            }
        } else {
            return new Response(json_encode(['error'=>'1', 'msg' => 'Please send request using post method.']));
        }
    }



}
