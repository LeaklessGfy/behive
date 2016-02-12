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

        $userTotal = $em->getRepository("AppBundle:User")->getCount();
        $gameTotal = $em->getRepository("AppBundle:Game")->getCount();
        $challengeTotal = $em->getRepository("AppBundle:Challenge")->getCount();

        return $this->render('pages/back/index.html.twig', array(
            "users" => $users,
            "games" => $games,
            "challenges" => $challenges,
            "userTotal" => $userTotal,
            "gameTotal" => $gameTotal,
            "challengeTotal" => $challengeTotal
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

        dump($entitiesArray);

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
        $entityForm = $this->getForm($ressource);
        $form = $this->createForm(new $entityForm(), $entity);

        $form->handleRequest($request);

        if($form->isValid()) {
            $this->handleUserPassword($ressource, $entity);
            $this->handleImage($entity, $ressource);
            $this->handleChallenge($ressource, $entity, $em);

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
        $entityForm = $this->getForm($ressource);
        $form = $this->createForm(new $entityForm(), $entity);

        $image = $entity->hasImage() ? $entity->hasImage()['get'] : null;

        $form->handleRequest($request);

        if($form->isValid()) {
            $this->handleUserPassword($entity, $ressource);
            $this->handleImage($entity,$ressource);
            $this->handleChallenge($ressource, $entity, $em);

            $em->flush();
            $this->addFlash('success', "Votre '$ressource' à bien été modifié");

            return $this->redirectToRoute("back_home");
        }

        return $this->render('pages/back/edit.html.twig', array(
            "form" => $form->createView(),
            "ressourceHelper" => $ressourceHelper,
            "image" => $image
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

    /**
     * @Route("/api/{game}/search", name="back_api_search")
     */
    public function searchVideoGame($game = null)
    {
//        $apiUrl = "http://thegamesdb.net/api/GetGamesList.php?name=".$game;
//        $rawGameList = file_get_contents($apiUrl);
//        dump($rawGameList);die;

        $apiUrl = "http://www.giantbomb.com/api/search/?";
        $apiKey = "api_key=838b1d8ea15ef015b443db5049548da60c4ed8d8";
        $format = "&format=json";
        $query = "&query=\"$game\"";
        $ressource = "&resources=game";

        $url = $apiUrl . $apiKey . $format . $query . $ressource;

        $rawGameList = file_get_contents($url);
        dump(json_decode($rawGameList));die;
    }
}
