<?php

namespace AppBundle\Service;

include __DIR__.'/../../../vendor/koraktor/steam-condenser/lib/steam-condenser.php';

class SteamApiService
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
