<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller
{
    /**
     * @Route("/challenge/participation/{id}", name="challenge_participation", requirements={
     *     "id": "\d+"
     * })
     */
    public function challengeParticipationAction($id)
    {
        $response = new JsonResponse();
        $response->setStatusCode(400);
        if($user = $this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $challenge = $em->getRepository("AppBundle:Challenge")->find($id);

            if(!$challenge) {
                $response->setStatusCode(404);
            }

            $challenge->addPlayer($user);
            $user->addChallenge($challenge);
            $em->flush();

            $response->setStatusCode(200);
        }

        return $response;
    }

    /**
     * @Route("/jeux/ajout/{id}", name="game_add", requirements={
     *     "id": "\d+"
     * })
     */
    public function gameAddAction($id)
    {
        $response = new JsonResponse();
        $response->setStatusCode(400);

        if($user = $this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $game = $em->getRepository("AppBundle:Game")->find($id);

            if(!$game) {
                $response->setStatusCode(404);
            }

            $user->addGame($game);
            $em->flush();
            $response->setStatusCode(200);
        }

        return $response;
    }

    /**
     * @Route("/jeux/retirer/{id}", name="game_remove", requirements={
     *     "id": "\d+"
     * })
     */
    public function gameRemoveAction($id)
    {
        $response = new JsonResponse();
        $response->setStatusCode(400);

        if($user = $this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $game = $em->getRepository("AppBundle:Game")->find($id);

            if(!$game) {
                $response->setStatusCode(404);
            }

            $user->removeGame($game);
            $em->flush();
            $response->setStatusCode(200);
        }

        return $response;
    }

    /**
     * @Route("/sync/steam", name="sync_steam")
     */
    public function syncSteamAction()
    {
        $response = new JsonResponse();
        $response->setStatusCode(401);

        if(!$user = $this->getUser()) {
            return $response;
        }

        $steamId = $user->getSteamID();

        $steamApi = $this->get('api.steam');
        $steamGames = $steamApi->getUserGames($steamId);

        if(isset($steamUser['error'])) {
            $response->setStatusCode(404);
            $explicit = array($steamUser['content'], $steamId);
            $response->setData($explicit);
            return $response;
        }

        $em = $this->getDoctrine()->getManager();
        $giantApi = $this->get('api.giant');

        $test = array("Witcher", "assassin's");

        foreach($steamGames as $stG) {
            $game = $em->getRepository("AppBundle:Game")->search($stG);

            if($game) {
                if(!in_array($game, $user->getGames()->getValues())) {
                    $user->addGame($game[0]);
                }
            } else {
                $giantApi->searchVideoGame($stG);
            }
        }


        $response->setStatusCode(200);
        $response->setData($steamGames);

        return $response;
    }
}
