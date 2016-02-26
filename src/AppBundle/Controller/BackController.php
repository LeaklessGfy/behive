<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class BackController extends BaseController
{
    /**
     * @Route("/", name="back_home")
     * @Method("GET")
     * @Template("pages/back/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")->findBy(array(), array("id" => "DESC"), 5, 0);
        $games = $em->getRepository("AppBundle:Game")->findBy(array(), array("id" => "DESC"), 5, 0);
        $challenges = $em->getRepository("AppBundle:Challenge")->findBy(array(), array("id" => "DESC"), 5, 0);

        $userTotal = $em->getRepository("AppBundle:User")->getCount();
        $gameTotal = $em->getRepository("AppBundle:Game")->getCount();
        $challengeTotal = $em->getRepository("AppBundle:Challenge")->getCount();

        return array(
            "users" => $users,
            "games" => $games,
            "challenges" => $challenges,
            "userTotal" => $userTotal,
            "gameTotal" => $gameTotal,
            "challengeTotal" => $challengeTotal
        );
    }

    /**
     * @Route("/{ressource}/list", name="back_list")
     * @Method("GET")
     * @Template("pages/back/list.html.twig")
     */
    public function listAction($ressource)
    {
        $ressourceHelper = $this->get('back.service')->getRessourceName($ressource);
        $this->checkIfExist($ressourceHelper);

        $em = $this->getDoctrine()->getManager();
        $ressource = ucfirst($ressource);
        $entities = $em->getRepository("AppBundle:".$ressource)->findAll();

        $entitiesArray = array();
        foreach($entities as $entity) {
            $entitiesArray[] = $entity->toArray();
        }

        return array(
            "entities" => $entitiesArray,
            "ressourceHelper" => $ressourceHelper
        );
    }


    /**
     * @Route("/{ressource}/create", name="back_create")
     * @Method({"GET", "POST"})
     * @Template("pages/back/create.html.twig")
     */
    public function createAction(Request $request, $ressource)
    {
        $ressourceHelper = $this->get('back.service')->getRessourceName($ressource);
        $em = $this->getDoctrine()->getManager();

        $this->checkIfExist($ressourceHelper);

        $entity = $this->get('back.service')->getNewEntity($ressource);
        $entityForm = $this->get('back.service')->getForm($ressource);
        $form = $this->createForm(new $entityForm(), $entity);

        $form->handleRequest($request);

        if($form->isValid()) {
            $this->get('back.service')->handleUserPassword($ressource, $entity);
            $this->get('back.service')->handleImage($entity, $ressource, null);
            $this->handleChallenge($ressource, $entity, $em);

            $em->persist($entity);
            $em->flush();
            $this->addFlash('success', "Votre $ressourceHelper à bien été créé");

            return $this->redirectToRoute("back_list", array("ressource" => $ressource));
        }

        return array(
            "form" => $form->createView(),
            "ressourceHelper" => $ressourceHelper
        );
    }


    /**
     * @Route("/{ressource}/{id}/edit", name="back_edit", requirements={
     *     "id": "\d+"
     * })
     * @Method({"GET", "POST"})
     * @Template("pages/back/edit.html.twig")
     */
    public function editAction(Request $request, $ressource, $id)
    {
        $ressourceHelper = $this->get('back.service')->getRessourceName($ressource);
        $em = $this->getDoctrine()->getManager();

        $this->checkIfExist($ressourceHelper);

        $entity = $em->getRepository("AppBundle:".ucfirst($ressource))->find($id);
        $this->checkIfExist($entity);
        $entityForm = $this->get('back.service')->getForm($ressource);
        $form = $this->createForm(new $entityForm(), $entity);

        $image = $entity->hasImage() ? $entity->hasImage()['get'] : null;
        $form->handleRequest($request);

        if($form->isValid()) {
            $this->get('back.service')->handleUserPassword($entity, $ressource);
            $this->get('back.service')->handleImage($entity, $ressource, $image);
            $this->handleChallenge($ressource, $entity, $em);

            $em->flush();
            $this->addFlash('success', "Votre $ressourceHelper à bien été modifié");

            return $this->redirectToRoute("back_list", array("ressource" => $ressource));
        }

        return array(
            "form" => $form->createView(),
            "ressourceHelper" => $ressourceHelper,
            "image" => $image
        );
    }

    /**
     * @Route("/{ressource}/{id}/delete", name="back_delete", requirements={
     *     "id": "\d+"
     * })
     * @Method("GET")
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

    /**
     * @Route("/helper", name="back_helper")
     * @Method("GET")
     */
    public function getGameFromApiAction(Request $request)
    {
        $games = array();
        $search = $request->get('search');

        if($search) {
            $api = $this->get('api.game');
            $games = $api->searchVideoGame($search)['results'];
        }

        return $this->render('pages/back/helper.html.twig', array(
            "games" => $games,
            "search" => $search
        ));
    }

    /**
     * @Route("/helper/save", name="back_helper_save")
     * @Method("POST")
     */
    public function saveGameFromApiAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $url = $request->get('url');
        $name = $request->get('name');

        $api = $this->get('api.game');
        $response = $api->getVideoGame($url, $name);

        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $editor = $em->getRepository('AppBundle:Editor')->findOneBy(array('name' => $response['results']['publishers'][0]['name']));

        $return = $this->get('load.game.service')->createGame($categories, $editor, $response['results']);

        foreach($return['categories'] as $category) {
            $em->persist($category);
        }
        if($return['editor']) {
            $em->persist($return['editor']);
        }
        $em->persist($return['game']);
        $em->flush();

        $this->addFlash('success', 'Le jeu à bien été créé');
        $id = $return['game']->getId();

        return $this->redirectToRoute('back_edit', array("ressource" => "game", "id" => $id));
    }
}
