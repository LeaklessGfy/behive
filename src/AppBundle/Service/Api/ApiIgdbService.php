<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\CacheService;
use Symfony\Component\Config\Definition\Exception\Exception;

class ApiIgdbService
{
    protected $cache;
    protected $apiBase;
    protected $apiKey;
    protected $api;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
        $this->apiBase = "https://www.igdb.com/api/v1/games";
        $this->apiKey = "14CFZTtk4mAG80O3L00gSFm6CyvK1fLRlnCnbuVHHng";
        $this->api = "ApiIgdb/";
    }

    public function callApi($id, $offset)
    {
        if($offset) {
            $offset= "?offset=$offset";
        }

        if(!$result = $this->cache->getInCache($id, $this->api)) {
            try {
                $curl = curl_init($this->apiBase.$offset);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Authorization: Token token="'.$this->apiKey.'"'));
                curl_setopt($curl, CURLOPT_VERBOSE, 0);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($curl);
            } catch(Exception $e) {
                return $e->getMessage();
            }

            $this->cache->storeInCache($id, $result, $this->api);
        }

        return json_decode($result, true);
    }
}
