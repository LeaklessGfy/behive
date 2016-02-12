<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class BackController extends BaseController
{
    /**
     * @Route("/", name="back_home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")->findBy(array(), array(), 5, 0);
        $games = $em->getRepository("AppBundle:Game")->findBy(array(), array(), 5, 0);
        $challenges = $em->getRepository("AppBundle:Challenge")->findBy(array(), array(), 5, 0);

        return $this->render('pages/back/index.html.twig', array(
            "users" => $users,
            "games" => $games,
            "challenges" => $challenges
        ));
    }

    /**
     * @Route("/{ressource}/list", name="back_list")
     */
    public function listAction($ressource)
    {
        $ressourceHelper = $this->getRessourceName($ressource);

        if(!$ressourceHelper) {
            $this->addFlash("danger", "Cette ressource n'existe pas");
            return $this->redirectToRoute('back_home');
        }

        $em = $this->getDoctrine()->getManager();
        $ressource = ucfirst($ressource);

        $entities = $em->getRepository("AppBundle:".$ressource)->findAll();
        $entitiesArray = array();
        foreach($entities as $entity) {
            $entitiesArray[] = $entity->toArray();
        }

        return $this->render('pages/back/list.html.twig', array(
            "entities" => $entitiesArray,
            "ressourceHelper" => $ressourceHelper
        ));
    }


    /**
     * @Route("/{ressource}/create", name="back_create")
     */
    public function createAction(Request $request, $ressource)
    {
        $ressourceHelper = $this->getRessourceName($ressource);
        $em = $this->getDoctrine()->getManager();

        if(!$ressourceHelper) {
            $this->addFlash("danger", "Cette ressource n'existe pas");
            return $this->redirectToRoute('back_home');
        }

        $entity = $this->getNewEntity($ressource);
        $entityForm = $this->getForm($entity);

        $form = $this->createForm(new $entityForm(), $entity);

        $form->handleRequest($request);

        if($form->isValid()) {
            $this->handleUserPassword($ressource, $entity);
            $this->handleImage($entity, $ressource);

            $em->persist($entity);
            $em->flush();
            $this->addFlash('success', "Votre '$ressource' à bien été créé");

            return $this->redirectToRoute("back_home");
        }

        return $this->render('pages/back/create.html.twig', array(
            "form" => $form->createView(),
            "ressourceHelper" => $ressourceHelper
        ));
    }


    /**
     * @Route("/{ressource}/{id}/edit", name="back_edit")
     */
    public function editAction(Request $request, $ressource, $id)
    {
        $ressourceHelper = $this->getRessourceName($ressource);
        $em = $this->getDoctrine()->getManager();

        if(!$ressourceHelper) {
            $this->addFlash("danger", "Cette ressource n'existe pas");
            return $this->redirectToRoute('back_home');
        }

        $ressource = ucfirst($ressource);

        $entity = $em->getRepository("AppBundle:".$ressource)->find($id);
        $entityForm = "AppBundle\\Form\\Type\\".$ressource."Type";

        $form = $this->createForm(new $entityForm(), $entity);
        $form->handleRequest($request);

        if($form->isValid()) {
            $this->handleUserPassword($ressource, $entity);
            $this->handleImage($entity,$ressource);

            $em->flush();
            $this->addFlash('success', "Votre '$ressource' à bien été modifié");

            return $this->redirectToRoute("back_home");
        }

        return $this->render('pages/back/edit.html.twig', array(
            "form" => $form->createView(),
            "ressourceHelper" => $ressourceHelper
        ));
    }

    /**
     * @Route("/{ressource}/{id}/delete", name="back_delete")
     */
    public function deleteAction($ressource, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ressource = ucfirst($ressource);

        $response = new Response();
        $response->setStatusCode("404");
        $response->setContent("Not found");

        $entity = $em->getRepository("AppBundle:".$ressource)->find(intval($id));

        if($entity) {
            $em->remove($entity);
            $em->flush();
            $response->setStatusCode("200");
            $response->setContent("Success");
        }

        return $response;
    }
}