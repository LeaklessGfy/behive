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
        return $this->render('pages/front/index.html.twig');
    }

    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function catalogueAction()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository("AppBundle:Game")->findAll();
        $categories = $em->getRepository("AppBundle:Category")->findAll();

        return $this->render('pages/front/catalogue.html.twig', array(
            "games" => $games,
            "categories" => $categories
        ));
    }

    /**
     * @Route("/challenge", name="challenge")
     */
    public function challengeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $challenge = $em->getRepository("AppBundle:Challenge")->findAll();
        $dailyChallenge = $em->getRepository("AppBundle:Challenge")->findOneBy(array('isDaily' => true));

        return $this->render('pages/front/challenge.html.twig', array(
            "challenge" => $challenge,
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
}
