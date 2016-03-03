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
            $response->setData(
                array(
                    "url" => $this->generateUrl('game_remove', array('id' => $id))
                )
            );

            $this->get('notification.service')->setNotification($user->getId(), "add-game", $game->getName());
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
            $response->setData(
                array(
                    "url" => $this->generateUrl('game_add', array('id' => $id))
                )
            );
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

        if(isset($steamGames['error'])) {
            $response->setStatusCode(404);
            $explicit = array($steamGames['content'], $steamId);
            $response->setData($explicit);
            return $response;
        }

        $em = $this->getDoctrine()->getManager();
        $gameService = $this->get('create.game.service');

        $gameAdd = array();
        foreach($steamGames as $stG) {
            $game = $em->getRepository("AppBundle:Game")->search($stG->getName());

            if($game) {
                if(!in_array($game[0], $user->getGames()->getValues())) {
                    $user->addGame($game[0]);
                }
            } else {
                $steamGame = $steamApi->getGameInfo($stG->getAppId(), $stG->getName());

                if($steamGame[$stG->getAppId()]['success'] === true) {
                    $data = $steamGame[$stG->getAppId()]['data'];

                    if(!in_array($data['name'], $gameAdd)) {
                        $game= $gameService->createGameFromSteam($data);
                        $user->addGame($game);
                        $gameAdd[] = $game->getName();
                    }
                }
            }

            $em->flush();
        }

        $response->setStatusCode(200);
        $response->setData(array("jeu ajouté : " => $gameAdd));

        return $response;
    }
}
