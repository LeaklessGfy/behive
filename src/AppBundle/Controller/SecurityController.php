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
    public function backLoginAction(Request $request)
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
    public function frontLoginAction(Request $request)
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
            $newUser->setLastConnexion(new \DateTime('now'));

            $role = $em->getRepository('AppBundle:RolesCustom')->findOneBy(array('role' => 'ROLE_USER'));
            $newUser->setRoles($role);

            $plainPassword = $newUser->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($newUser, $plainPassword);
            $newUser->setPassword($encoded);

            $em->persist($newUser);
            $em->flush();

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
