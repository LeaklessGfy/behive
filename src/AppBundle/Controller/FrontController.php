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

        $challenges = $em->getRepository("AppBundle:Challenge")->findAll();
        $dailyChallenge = $em->getRepository("AppBundle:Challenge")->findOneBy(array('isDaily' => true));

        return $this->render('pages/front/challenge.html.twig', array(
            "challenges" => $challenges,
            "dailyChallenge" => $dailyChallenge
        ));

    }

    /**
     * @Route("/profil", name="profil")
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
        $user = $this->getUser();
        if($user) {
            $userGames = $user->getGames();
            dump($userGames);die;
            foreach($userGames as $game) {
                if($game->getId() === $id) {
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
            $response->setStatusCode(404);

            if($game) {
                $user->addGame($game);
                $em->flush();
                $response->setStatusCode(200);
            }
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
            if($games) {
                $games = $games->getGames();
            }
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
