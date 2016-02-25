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

        $games = $em->getRepository("AppBundle:Game")->findBy(array(), array("id" => "DESC"), 8, 0);
        $categories = $em->getRepository("AppBundle:Category")->findAll();

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
     * @Route("/list/{search}", name="search", defaults={"search" = null})
     */
    public function searchAction($search)
    {
        $em = $this->getDoctrine()->getManager();

        if($search) {
            $games = $em->getRepository('AppBundle:Game')->findBy(array("name" => $search));
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
