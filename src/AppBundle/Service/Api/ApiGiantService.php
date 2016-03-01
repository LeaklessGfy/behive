<?php

namespace AppBundle\Service\Api;

class ApiGiantService extends ApiCaller
{
    protected $apiBase;
    protected $apiKey;
    protected $format;
    protected $apiGiant;

    public function init()
    {
        $this->apiBase = "http://www.giantbomb.com/api/";
        $this->apiKey = "api_key=838b1d8ea15ef015b443db5049548da60c4ed8d8";
        $this->format = "&format=json";
        $this->api = "ApiGiant";
    }

    private function urlConstructor($base, $action = "", $query = "", $parameters = "", $fieldList = "")
    {
        $url = $base . $action . "?" . $this->apiKey . $this->format . $query . $parameters . $fieldList;

        return $url;
    }

    public function searchVideoGame($game = null)
    {
        $this->init();

        $action = "search/";
        $query = "&query=\"$game\"";
        $parameter = "&resources=game";
        $fieldList = "&field_list=name,image,deck,api_detail_url";

        $url = $this->urlConstructor($this->apiBase, $action, $query, $parameter, $fieldList);
        $id = $this->idConstructor($action, $query);

        return $this->callApi($id, $url, $this->api);
    }

    public function getVideoGame($url, $name)
    {
        $this->init();

        $fieldList = "&field_list=name,image,deck,original_release_date,publishers,genres,original_game_rating,id";

        $url = $this->urlConstructor($url, '', '', '', $fieldList);
        $id = $this->idConstructor("game", $name);

        return $this->callApi($id, $url, $this->api);
    }
}
