<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    protected function callApi($interface, $method, $steamId)
    {
        $url = $this->apiBaseUrl . "/" . $interface . "/" . $method . "/" . $this->apiVersion . "/&key=" . $this->apiKey . "&steamid=" . $steamId;
    }

    protected function getPlayerAchievement($steamId)
    {
        $interface = "ISteamUserStats";
        $method = "GetPlayerAchievements";

        $response = $this->callApi($interface, $method, $steamId);
    }

    protected function getRessourceName($ressource)
    {
        $nameHelp = array(
            "user" => "utilisateur",
            "game" => "jeux vidéo",
            "editor" => "éditeur",
            "challenge" => "challenge",
            "badge" => "badge",
            "category" => "categorie"
        );

        if(!isset($nameHelp[$ressource])) {
            return false;
        }

        return $nameHelp[$ressource];
    }

    protected function getNewEntity($ressource)
    {
        $ressource = ucfirst($ressource);

        $entity= "AppBundle\\Entity\\".$ressource;
        return new $entity();
    }

    protected function getForm($ressource)
    {
        $ressource = ucfirst($ressource);

        return "AppBundle\\Form\\Type\\".$ressource."Type";
    }

    protected function handleImage($entity, $ressource)
    {
        $ressource = strtolower($ressource);

        if($hasImage = $entity->hasImage()) {
            $file = $hasImage["get"];

            if($file) {
                $fileName = $ressource."-".time()."-img.jpg";
                $fileDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$ressource;
                $file->move($fileDir, $fileName);

                $entity->$hasImage["set"]("uploads/".$ressource."/".$fileName);
            }
        }
    }

    protected function handleUserPassword($ressource, $entity)
    {
        if($ressource === "User") {
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($password);
        }
    }
}