<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends Controller
{
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
