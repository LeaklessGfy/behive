<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     * @Template("pages/front/index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/catalogue", name="catalogue")
     * @Method("GET")
     * @Template("pages/front/catalogue.html.twig")
     */
    public function catalogueAction()
    {
        $em = $this->getDoctrine()->getManager();

        $games = $em->getRepository("AppBundle:Game")->findBy(array(), array("id" => "DESC"), 8, 0);
        $categories = $em->getRepository("AppBundle:Category")->findAll();

        $action = $em->getRepository("AppBundle:Game")->findByCategory("Action-Adventure", 7);
        $fps = $em->getRepository("AppBundle:Game")->findByCategory("First-Person Shooter", 7);
        $rpg = $em->getRepository("AppBundle:Game")->findByCategory("Role-Playing", 7);

        return array(
            "games" => $games,
            "categories" => $categories,
            "action" => $action,
            "fps" => $fps,
            "rpg" => $rpg
        );
    }

    /**
     * @Route("/challenge", name="challenge")
     * @Method("GET")
     * @Template("pages/front/challenge.html.twig")
     */
    public function challengeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $challenges = $em->getRepository("AppBundle:Challenge")->findNonDaily();
        $dailyChallenge = $em->getRepository("AppBundle:Challenge")->findDaily();

        $hasIt = $this->get('front.service')->hasChallenges($this->getUser(), $dailyChallenge, $challenges);

        return array(
            "challenges" => $challenges,
            "dailyChallenge" => $dailyChallenge,
            "hasIt" => $hasIt
        );
    }

    /**
     * @Route("/challenge/{id}", name="challenge_detail", requirements={
     *     "id": "\d+"
     * })
     * @Method("GET")
     * @Template("pages/front/challenge_detail.html.twig")
     */
    public function challengeDetailAction($id)
    {
        $challenge = $this->getDoctrine()->getRepository("AppBundle:Challenge")->find($id);

        if(!$challenge) {
            return $this->redirectToRoute('challenge');
        }

        $hasIt = $this->get('front.service')->hasChallenge($this->getUser(), $challenge);

        return array(
            "challenge" => $challenge,
            "hasIt" => $hasIt
        );
    }

    /**
     * @Route("/profil", name="profil")
     * @Method({"GET", "POST"})
     * @Template("pages/front/profil.html.twig")
     */
    public function profilAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->getProfil($this->getUser());
        $form = $this->createForm(new UserEditType(), $user);
        $avatar = $user->getAvatar();

        $form->handleRequest($request);

        if($form->isValid()) {
            //AVATAR
            $this->get('front.service')->handleAvatar($user, $avatar);
            $em->flush();

            return $this->redirectToRoute("profil");
        }

        return array(
            "form" => $form->createView(),
            "user" => $user
        );
    }

    /**
     * @Route("/behiver/{id}", name="user")
     * @Method("GET")
     * @Template("pages/front/profil.html.twig")
     */
    public function userAction($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->getProfil($id);

        return array(
            "user" => $user
        );
    }

    /**
     * @Route("/profil/active_badge/{id}", name="profil_active_badge")
     * @Method("GET")
     */
    public function changeActiveBadge($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->setActiveBadge($id);

        $em->flush();
        $this->addFlash("success", "Votre badge à bien été changé");

        return $this->redirectToRoute("profil");
    }

    /**
     * @Route("/jeux/{slug}", name="game", requirements={
     *     "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     * @Template("pages/front/game.html.twig")
     */
    public function gameAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository("AppBundle:Game")->findBySlug($slug);

        if(!$game) {
            return $this->redirectToRoute('catalogue');
        }

        $hasIt = $this->get('front.service')->hasGame($this->getUser(), $game);

        $return = array(
            "game" => $game,
            "hasIt" => $hasIt
        );

        if($this->getUser()) {
            $comment = new Comment($game, $this->getUser());
            $form = $this->createForm(new CommentType(), $comment);
            $form->handleRequest($request);

            if($form->isValid()) {
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute("game", array("slug" => $slug));
            }

            $return["form"] = $form->createView();
        }

        return $return;
    }

    /**
     * @Route("/liste", name="search")
     * @Method("GET")
     * @Template("pages/front/listing.html.twig")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = urldecode($request->get('filter'));
        $search = $request->get('search');
        if($search) {
            $games = $em->getRepository('AppBundle:Game')->search($search, true);
            $re = "le jeu : " . $search;
        } elseif($filter) {
            $games = $em->getRepository('AppBundle:Game')->findByCategory($filter, null);
            $re = "le filtre : " . $filter;
        } else {
            $games = $em->getRepository('AppBundle:Game')->findBy(array(), array(), 8, 0);
            $re = null;
        }

        $categories = $em->getRepository("AppBundle:Category")->findAll();

        return array(
            "games" => $games,
            "categories" => $categories,
            "re" => $re
        );
    }
}
