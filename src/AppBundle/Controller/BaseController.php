<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends Controller
{
    private $apiKey;
    private $apiBaseUrl;
    private $apiVersion;
    private $apiId;

    public function __construct()
    {
        $this->apiKey = "EB2471058B905E6A6D6036A38C135015";
        $this->apiBaseUrl = " http://api.steampowered.com/";
        $this->apiVersion = "v0001";
        $this->apiId = "440";
    }

    protected function checkIfExist($ressourceHelper)
    {
        if(!$ressourceHelper) {
            $this->addFlash("danger", "Cette ressource n'existe pas");
            throw new HttpException(307, null, null, array('Location' => $this->generateUrl('back_home')));
        }
    }

    protected function handlePostProcess($entity, $ressource, $image, $em)
    {
        $this->get('back.service')->handleUserPassword($ressource, $entity);
        $this->get('back.service')->handleImage($entity, $ressource, $image);
        $this->handleChallenge($ressource, $entity, $em);
    }

    protected function handleChallenge($ressource, $entity, $em)
    {
        if($ressource === "challenge") {
            $limits = $entity->getLimits();

            foreach($limits as $limit) {
                $limit->setChallenge($entity);
                $em->persist($limit);
            }

            if($entity->getIsDaily()) {
                $em->getRepository("AppBundle:Challenge")->clearDaily();
            }
        }
    }
}
