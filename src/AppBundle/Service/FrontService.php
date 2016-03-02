<?php
namespace AppBundle\Service;

class FrontService
{
    public function hasChallenges($user, $dailyChallenge, $challenges)
    {
        $hasIt = array();
        if($user) {
            if(in_array($user, $dailyChallenge->getPlayers()->getValues())) {
                $hasIt[] = $dailyChallenge->getId();
            }
            foreach($challenges as $challenge) {
                if(in_array($user, $challenge->getPlayers()->getValues())) {
                    $hasIt[] = $challenge->getId();
                }
            }
        }

        return $hasIt;
    }

    public function hasGame($user, $game)
    {
        $hasIt = false;
        if($user && in_array($user, $game->getOwners()->getValues())) {
            $hasIt = true;
        }

        return $hasIt;
    }

    public function hasChallenge($user, $challenge)
    {
        $hasIt = false;
        if($user) {
            $userChallenges = $user->getChallenges()->getValues();

            if(in_array($challenge, $userChallenges)) {
                $hasIt = true;
            }
        }

        return $hasIt;
    }
}
