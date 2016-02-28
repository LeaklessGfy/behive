<?php

namespace AppBundle\Controller;

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

        $challenges = $em->getRepository("AppBundle:Challenge")->findBy(array('isDaily' => false));
        $dailyChallenge = $em->getRepository("AppBundle:Challenge")->findOneBy(array('isDaily' => true));

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
        $user = $this->getUser();
        $form = $this->createForm(new UserEditType(), $user);
        $avatar = $user->getAvatar();
        $positions = $this->getDoctrine()->getRepository("AppBundle:ChallengePosition")->findBy(array("user" => $user));
        $awards = array();
        foreach($positions as $position) {
            $awards[] = $position->getAward();
        }

        $form->handleRequest($request);

        if($form->isValid()) {
            //AVATAR
            $file = $user->getAvatar();
            if($file) {
                $fileName = "user-".time()."-img.jpg";
                $fileDir = $this->getParameter('upload.dir')."user/";
                $file->move($fileDir, $fileName);

                $user->setAvatar("uploads/user/".$fileName);
            } else {
                $user->setAvatar($avatar);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("profil");
        }

        return array(
            "form" => $form->createView(),
            "positions" => $positions,
            "awards" => $awards
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
     * @Route("/jeux/{id}", name="game", requirements={
     *     "id": "\d+"
     * })
     * @Method("GET")
     * @Template("pages/front/game.html.twig")
     */
    public function gameAction($id)
    {
        $game = $this->getDoctrine()->getRepository("AppBundle:Game")->find($id);

        if(!$game) {
            return $this->redirectToRoute('catalogue');
        }

        $hasIt = $this->get('front.service')->hasGame($this->getUser(), $game);

        return array(
            "game" => $game,
            "hasIt" => $hasIt
        );
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
            $games = $em->getRepository('AppBundle:Game')->search($search, $filter);
        } elseif($filter) {
            $games = $em->getRepository('AppBundle:Category')->findOneBy(array("name" => $filter));
            $games = $games->getGames();
        } else {
            $games = $em->getRepository('AppBundle:Game')->findBy(array(), array(), 8, 0);
        }

        $categories = $em->getRepository("AppBundle:Category")->findAll();

        return array(
            "games" => $games,
            "categories" => $categories
        );
    }
}
