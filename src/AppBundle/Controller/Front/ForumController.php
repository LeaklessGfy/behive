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
        return $this->render('pages/front/forum.html.twig');
    }
}
