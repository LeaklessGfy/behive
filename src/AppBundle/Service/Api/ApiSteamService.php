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

        }

        $user = $response['content'];
        $games = $user->getGames();

        return $games;
    }
}
