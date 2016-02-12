<?php

namespace AppBundle\Service;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Exception\IOException;

class ApiGiant
{
    protected $cache;
    protected $apiBase;
    protected $apiKey;
    protected $format;

    public function __construct()
    {
        $this->apiBase = "http://www.giantbomb.com/api/";
        $this->apiKey = "api_key=838b1d8ea15ef015b443db5049548da60c4ed8d8";
        $this->format = "&format=json";
    }

    protected function callApi($action, $query, $parameters)
    {
        $url = $this->apiBase . $action . "/?" . $this->apiKey . $this->format . $query . $parameters;

        $id = $action . "-" . $query;
        $id = str_replace("=", "-", $id);
        $id = preg_replace('/[^a-zA-Z0-9-_\.]/','', $id);

        if (!$result = $this->getInCache($id)) {
            try {
                $result = file_get_contents($url);
            } catch(Exception $e) {
                return $e->getMessage();
            }

            $this->storeInCache($id, $result);
        }

        return json_decode($result, true);
    }

    protected function getInCache($id)
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in(__DIR__.'/../Cache/ApiGiant/')->name($id.'.json');

        $contents = "";
        foreach ($finder as $file) {
            $contents = $file->getContents();
        }

        if(isset($contents)) {
            dump("cache");
            return $contents;
        }

        return false;
    }

    protected function storeInCache($id, $result)
    {
        $fs = new \Symfony\Component\Filesystem\Filesystem();

        try {
            $fs->dumpFile(__DIR__.'/../Cache/ApiGiant/'.$id.'.json', $result);
        }
        catch(IOException $e) {
        }
    }

    public function searchVideoGame($game = null)
    {
//        $apiUrl = "http://thegamesdb.net/api/GetGamesList.php?name=".$game;
//        $rawGameList = file_get_contents($apiUrl);
//        dump($rawGameList);die;

        $action = "search";
        $query = "&query=\"$game\"";
        $parameter = "&resources=game";

        return $this->callApi($action, $query, $parameter);
    }
}