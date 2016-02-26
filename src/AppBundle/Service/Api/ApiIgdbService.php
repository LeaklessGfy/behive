<?php

namespace AppBundle\Service\Api;

use AppBundle\Service\CacheService;
use Symfony\Component\Config\Definition\Exception\Exception;

class ApiIgdbService
{
    protected $cache;
    protected $apiBase;
    protected $apiKey;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
        $this->apiBase = "https://www.igdb.com/api/v1/games";
        $this->apiKey = "14CFZTtk4mAG80O3L00gSFm6CyvK1fLRlnCnbuVHHng";
    }

    protected function callApi($id)
    {
        if (!$result = $this->cache->getInCache($id)) {
            try {
                $curl = curl_init($this->apiBase);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Authorization: Token token="'.$this->apiKey.'"'));
                curl_exec ($curl);
                $result = curl_getinfo( $curl );
            } catch(Exception $e) {
                return $e->getMessage();
            }

            $this->cache->storeInCache($id, $result);
        }

        return json_decode($result, true);
    }
}
