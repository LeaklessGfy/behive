<?php

namespace AppBundle\Service\Api;

include __DIR__.'/../../../vendor/koraktor/steam-condenser/lib/steam-condenser.php';

class ApiSteamService
{
    public function getApi()
    {
        //$steam = new \SteamId('rskoo');
        $id = \SteamId::create('rskoo');
        $stats = $id->getGameStats('tf2');
        $achievements = $stats->getAchievements();

        dump($achievements);die;
    }
}
