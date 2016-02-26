<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('pages/front/index.html.twig');
    }

    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogueAction()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository("AppBundle:Game")->findBy(array(), array("id" => "DESC"), 8, 0);
        $categories = $em->getRepository("AppBundle:Category")->findAll();

        $action = null;
        $fps = null;
        $rpg= null;
        foreach($categories as $category) {
            if($category->getName() === "Action-Adventure") {
                $action = $category;
            } elseif($category->getName() === "First-Person Shooter") {
                $fps = $category;
            } elseif($category->getName() === "Role-Playing") {
                $rpg = $category;
            }
        }

        return $this->render('pages/front/catalogue.html.twig', array(
            "games" => $games,
            "categories" => $categories,
            "action" => $action,
            "fps" => $fps,
            "rpg" => $rpg
        ));
    }

    /**
     * @Route("/challenge", name="challenge")
     */
    public function challengeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $challenges = $em->getRepository("AppBundle:Challenge")->findBy(array('isDaily' => false));
        $dailyChallenge = $em->getRepository("AppBundle:Challenge")->findOneBy(array('isDaily' => true));

        $hasIt = array();
        if($user = $this->getUser()) {
            $userChallenge = $user->getChallenges()->getValues();
            foreach($userChallenge as $challenge) {
                if($challenge === $dailyChallenge) {
                    $hasIt[] = $dailyChallenge->getId();
                }

                if(in_array($challenge, $challenges)) {
                    $hasIt[] = $challenge->getId();
                }
            }
        }

        return $this->render('pages/front/challenge.html.twig', array(
            "challenges" => $challenges,
            "dailyChallenge" => $dailyChallenge,
            "hasIt" => $hasIt
        ));

    }

    /**
     * @Route("/challenge/participation/{id}", name="challenge_participation")
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
     * @Route("/profile", name="profil")
     */
    public function profilAction()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository("AppBundle:Game")->findAll();
        $categories = $em->getRepository("AppBundle:Category")->findAll();

        return $this->render('pages/front/profil.html.twig', array(
            "games" => $games,
            "categories" => $categories
        ));
    }

    /**
     * @Route("/utilisateur", name="user")
     */
    public function userAction()
    {
        return $this->render('pages/front/user.html.twig');
    }

    /**
     * @Route("/jeux/{id}", name="game", requirements={
     *     "id": "\d+"
     * })
     */
    public function gameAction($id)
    {
        $game = $this->getDoctrine()->getRepository("AppBundle:Game")->find($id);

        if(!$game) {
            return $this->redirectToRoute('catalogue');
        }

        $hasIt = false;
        if($user = $this->getUser()) {
            $userGames = $user->getGames()->getValues();
            foreach($userGames as $userGame) {
                if($userGame->getId() == $id) {
                    $hasIt = true;
                }
            }
        }

        return $this->render('pages/front/game.html.twig', array(
            "game" => $game,
            "hasIt" => $hasIt
        ));
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
     * @Route("/liste/{search}", name="search", defaults={"search" = null})
     */
    public function searchAction($search)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = urldecode($this->getRequest()->get('filter'));
        if($search) {
            $games = $em->getRepository('AppBundle:Game')->search($search, $filter);
        } elseif($filter) {
            $games = $em->getRepository('AppBundle:Category')->findOneBy(array("name" => $filter));
        } else {
            $games = $em->getRepository('AppBundle:Game')->findBy(array(), array(), 8, 0);
        }

        $categories = $em->getRepository("AppBundle:Category")->findAll();

        return $this->render('pages/front/listing.html.twig', array(
            "games" => $games,
            "categories" => $categories
        ));
    }
}
