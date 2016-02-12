<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class SecurityController extends Controller
{
    /**
     * @Route("/admin/login", name="back_login")
     */
    public function backLoginAction(Request $request)
    {
        $encoder = $this->container->get('security.password_encoder');
        //$user = new User();
        //$password = $encoder->encodePassword($user, "test");

        //dump($password);

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'pages/back/security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
}