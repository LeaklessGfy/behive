<?php

namespace AppBundle\Service\Api;

include __DIR__.'/../../../../vendor/koraktor/steam-condenser/lib/steam-condenser.php';

class ApiSteamService extends ApiCaller
{
    public function getUser($steamID)
    {
        try {
            $user = \SteamId::create($steamID);
        } catch(\SteamCondenserException $e) {
            return array("error" => true, "content" => $e->getMessage());
        }

        return array("error" => false, "content" => $user);
    }

    public function getUserGames($steamID)
    {
        $response = $this->getUser($steamID);
        if($response["error"]) {
            return $response;
        }

        $user = $response['content'];
        $games = $user->getGames();

        return $games;
    }

    public function getGameInfo($appid, $name)
    {
        $base = "http://store.steampowered.com/api/appdetails?appids=";
        $region = "&cc=fr";

        $url = $base.$appid.$region;
        $id = $this->idConstructor("steam-game", $name);

        return $this->callApi($id, $url, "Steam");
    }
}
