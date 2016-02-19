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

        return $this->render('pages/front/forum.html.twig');
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
}
