<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserFrontType;
use Doctrine\Tests\Common\DataFixtures\TestEntity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/admin/login", name="back_login")
     */
    public function backLoginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
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

    /**
     * @Route("/connexion", name="front_login")
     */
    public function frontLoginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'pages/front/security/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function registerAction(Request $request)
    {
        $newUser = new User();
        $form = $this->createForm(new UserFrontType(), $newUser);

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Image
            $this->get('front.service')->handleAvatar($newUser, "img/avatar.gif");

            //Role
            $role = $em->getRepository('AppBundle:RolesCustom')->findOneBy(array('role' => 'ROLE_USER'));
            $newUser->setRoles($role);

            //Password
            $plainPassword = $newUser->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($newUser, $plainPassword);
            $newUser->setPassword($encoded);

            $em->persist($newUser);
            $em->flush();

            $this->get('notification.service')->setNotification($newUser->getId(), "new-user", $newUser->getUsername());

            $this->addFlash("success", "Votre compte à bien été validé. Veuillez vous connecter maintenant !");
            return $this->redirectToRoute('front_login');
        }

        return $this->render(
            'pages/front/security/register.html.twig',
            array(
                "form" => $form->createView()
            )
        );
    }
}
