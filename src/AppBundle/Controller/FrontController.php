<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        dump($this->container->getParameter('upload.dir'));
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

        $mainCategories = $this->get("front.service")->getMainCategories($categories);

        return $this->render('pages/front/catalogue.html.twig', array(
            "games" => $games,
            "categories" => $categories,
            "action" => $mainCategories['action'],
            "fps" => $mainCategories['fps'],
            "rpg" => $mainCategories['rpg']
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

        $hasIt = $this->get('front.service')->hasChallenge($this->getUser(), $dailyChallenge, $challenges);

        return $this->render('pages/front/challenge.html.twig', array(
            "challenges" => $challenges,
            "dailyChallenge" => $dailyChallenge,
            "hasIt" => $hasIt
        ));
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

        $hasIt = $this->get('front.service')->hasGame($this->getUser(), $game);

        return $this->render('pages/front/game.html.twig', array(
            "game" => $game,
            "hasIt" => $hasIt
        ));
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
