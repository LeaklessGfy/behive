<?php

namespace AppBundle\Controller\Front;

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
        $subjects = $this->getDoctrine()->getRepository("AppBundle:FSubject")->findBy(array(), array('lastUpdate' => 'DESC'), 8, 0);

        return $this->render('pages/front/forum.html.twig', array(
            "subjects" => $subjects
        ));
    }

    /**
     * @Route("/privé", name="forum_prive")
     */
    public function recentAction()
    {
        return $this->render('pages/front/forum.html.twig');
    }

    /**
     * @Route("/explorer", name="forum_explore")
     */
    public function exploreAction()
    {
        return $this->render('pages/front/forum.html.twig');
    }

    /**
     * @Route("/parametres", name="forum_param")
     */
    public function parametereAction()
    {
        return $this->render('pages/front/forum.html.twig');
    }

    /**
     * @Route("/sujet/{id}", name="forum_subject")
     */
    public function subjectAction($id)
    {
        return $this->render('pages/front/forum.html.twig');
    }

    /**
     * @Route("/sujet/créer", name="forum_subject_create")
     */
    public function createSubjectAction($id)
    {
        return $this->render('pages/front/forum.html.twig');
    }
}
