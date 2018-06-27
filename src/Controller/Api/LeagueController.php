<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\League;


class LeagueController extends Controller
{

    public function getRepo(){
        return $this->getDoctrine()->getRepository(League::class);
    }


   /**
     * @Route("/api/league/{name}/team-list", name="api_league")
     */
    public function teams(Request $request, $name) {
        //check token validation
        $tokenAuth = $request->headers->get('Authorization');
        $response = $this->get('my_helper')->authenticate($tokenAuth);
            if ($response === true && $name != "") {
                // get team list by the league name
                $teams = $this->getRepo()->getTeams($name);
                if($teams){
                    return new Response(json_encode($teams));
                } else {
                    return new Response(json_encode(['error'=>'1', 'msg' => 'Oops no team available.']));
                }
            } else {
                return new Response($response);
            }
    }


    /**
     * @Route("/api/delete-league/{id}", name="api_delete_league")
     */
    public function delete(Request $request,$id) {
        //check token validation
        $tokenAuth = $request->headers->get('Authorization');
        $response = $this->get('my_helper')->authenticate($tokenAuth);
        if ($response === true ) {
                // delete league by the id
                $league = $this->getRepo()->delete($id);
                if($league){
                    return new Response(json_encode(['success'=>'1', 'msg' => 'League removed successfully from the system.']));
                } else {
                    return new Response(json_encode(['error'=>'1', 'msg' => 'League does not exist']));
                }

            } else {
                return new Response($response);
            }
       
    }



}
