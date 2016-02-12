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

    protected function callApi($interface, $method, $steamId) {
        $url = $this->apiBaseUrl . "/" . $interface . "/" . $method . "/" . $this->apiVersion . "/&key=" . $this->apiKey . "&steamid=" . $steamId;
    }

    protected function getPlayerAchievement($steamId) {
        $interface = "ISteamUserStats";
        $method = "GetPlayerAchievements";

        $response = $this->callApi($interface, $method, $steamId);
    }

    protected function getRessourceName($ressource) {
        $nameHelp = array(
            "user" => "utilisateur",
            "game" => "jeux vidéo",
            "editor" => "éditeur",
            "challenge" => "challenge",
            "badge" => "badge",
            "category" => "categorie"
        );

        return $nameHelp[$ressource];
    }
}