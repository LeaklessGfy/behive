<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends BaseController
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

        return $this->render('pages/front/challenge.html.twig');
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function forumAction()
    {
        return $this->render('pages/front/forum.html.twig');
    }

    /**
     * @Route("/utilisateur", name="user")
     */
    public function userAction()
    {
        return $this->render('pages/front/user.html.twig');
    }
}
