<?php

namespace AppBundle\Controller\Forum;

use AppBundle\Entity\FSubject;
use AppBundle\Form\Type\FSubjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/forum")
 */
class ForumController extends Controller
{
    /**
     * @Route("/", name="forum_home")
     */
    public function indexAction()
    {
        //$steam = $this->get('steam.api');
        //$steam->getApi();
        $subjects = $this->getDoctrine()->getRepository("AppBundle:FSubject")
                    ->findBy(array('isPrivate' => false), array('lastUpdate' => 'DESC'), 8, 0);

        return $this->render('pages/front/forum.html.twig', array(
            "subjects" => $subjects
        ));
    }

    /**
     * @Route("/privé", name="forum_prive")
     */
    public function privateAction()
    {
        $id = $this->getUser()->getId();
        $subjects = $this->getDoctrine()->getRepository("AppBundle:FSubject")
                    ->findBy(array('isPrivate' => true, 'owner' => $id), array('lastUpdate' => 'DESC'));

        return $this->render('pages/front/forum/private.html.twig', array(
            "subjects" => $subjects
        ));
    }

    /**
     * @Route("/explorer", name="forum_explore")
     */
    public function exploreAction()
    {
        $games = $this->getDoctrine()->getRepository("AppBundle:Game")->findBy(array(), array('id' => 'DESC'), 8, 0);

        return $this->render('pages/front/forum/explore.html.twig', array(
            "games" => $games
        ));
    }

    /**
     * @Route("/explorer/{id}", name="forum_explore_game")
     */
    public function exploreByGameAction($id)
    {
        $game = $this->getDoctrine()->getRepository("AppBundle:Game")->find($id);

        return $this->render('pages/front/forum/explore_game.html.twig', array(
            "game" => $game
        ));
    }

    /**
     * @Route("/parametres", name="forum_param")
     */
    public function parametereAction()
    {
        return $this->render('pages/front/forum.html.twig');
    }

    /**
     * @Route("/sujet/créer", name="forum_subject_create")
     */
    public function createSubjectAction(Request $request)
    {
        $subject = new FSubject();
        $form = $this->createForm(new FSubjectType(), $subject);

        $form->handleRequest($request);

        if($form->isValid()) {
            $subject->setIsPrivate(false);
            $subject->setOwner($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            $this->addFlash('success', 'Sujet bien créé');
            return $this->redirectToRoute('forum_subject', array('id' => $subject->getId()));
        }

        return $this->render('pages/front/forum/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/sujet/{id}", name="forum_subject")
     */
    public function subjectAction($id)
    {
        $subject = $this->getDoctrine()->getRepository('AppBundle:FSubject')->find($id);

        return $this->render('pages/front/forum/subject.html.twig', array(
            "subject" => $subject
        ));
    }
}
