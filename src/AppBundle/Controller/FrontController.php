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

        $action = $em->getRepository("AppBundle:Game")->findByCategory("Action-Adventure", 7);
        $fps = $em->getRepository("AppBundle:Game")->findByCategory("First-Person Shooter", 7);
        $rpg = $em->getRepository("AppBundle:Game")->findByCategory("Role-Playing", 7);

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

        $hasIt = $this->get('front.service')->hasChallenges($this->getUser(), $dailyChallenge, $challenges);

        return $this->render('pages/front/challenge.html.twig', array(
            "challenges" => $challenges,
            "dailyChallenge" => $dailyChallenge,
            "hasIt" => $hasIt
        ));
    }

    /**
     * @Route("/challenge/{id}", name="challenge_detail", requirements={
     *     "id": "\d+"
     * })
     */
    public function challengeDetailAction($id)
    {
        $challenge = $this->getDoctrine()->getRepository("AppBundle:Challenge")->find($id);

        if(!$challenge) {
            return $this->redirectToRoute('challenge');
        }

        $hasIt = $this->get('front.service')->hasChallenge($this->getUser(), $challenge);

        return $this->render('pages/front/challenge_detail.html.twig', array(
            "challenge" => $challenge,
            "hasIt" => $hasIt
        ));
    }

    /**
     * @Route("/profile", name="profil")
     */
    public function profilAction()
    {
        return $this->render('pages/front/profil.html.twig', array(
        ));
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
     * @Route("/liste", name="search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = urldecode($request->get('filter'));
        $search = $request->get('search');
        if($search) {
            $games = $em->getRepository('AppBundle:Game')->search($search, $filter);
        } elseif($filter) {
            $games = $em->getRepository('AppBundle:Category')->findOneBy(array("name" => $filter));
            $games = $games->getGames();
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
