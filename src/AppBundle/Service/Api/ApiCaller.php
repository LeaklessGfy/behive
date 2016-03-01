<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\Cache\CacheService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\Session;

class ApiCaller
{
    protected $cache;
    private $session;

    public function __construct(CacheService $cache, Session $session)
    {
        $this->cache = $cache;
        $this->session = $session;
    }

    protected function callApi($id, $url, $subDir = "raw")
    {
        $msg = "Donnée provenant du cache";
        if (!$result = $this->cache->getInCache($id, $subDir)) {
            try {
                $options = array(
                    "http"=>array(
                        "header"=>"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
            } catch(Exception $e) {
                return $e->getMessage();
            }

            $msg = "Donnée provenant de l'Api";
            $this->cache->storeInCache($id, $result, $subDir);
        }

        $this->session->getFlashBag()->add("warning", $msg);

        return json_decode($result, true);
    }

    protected function idConstructor($action, $query)
    {
        $id = $action . "-" . $query;
        $id = str_replace("=", "-", $id);
        $id = preg_replace('/[^a-zA-Z0-9-_\.]/','', $id);
        $id = strtolower($id);

        return $id;
    }
}
