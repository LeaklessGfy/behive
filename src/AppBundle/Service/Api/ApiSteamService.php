<?php

namespace AppBundle\Service\Api;

include __DIR__.'/../../../../vendor/koraktor/steam-condenser/lib/steam-condenser.php';

class ApiSteamService
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

        $gameName = array();
        foreach($games as $game) {
            $gameName[] = $game->getName();
        }

        return $gameName;
    }
}
