<?php
namespace AppBundle\Service;

class FrontService
{
    public function hasChallenges($user, $dailyChallenge, $challenges)
    {
        $hasIt = array();
        if($user) {
            $userChallenge = $user->getChallenges()->getValues();
            foreach($userChallenge as $challenge) {
                if($challenge === $dailyChallenge) {
                    $hasIt[] = $dailyChallenge->getId();
                }

                if(in_array($challenge, $challenges)) {
                    $hasIt[] = $challenge->getId();
                }
            }
        }

        return $hasIt;
    }

    public function hasGame($user, $game)
    {
        $hasIt = false;
        if($user) {
            $userGames = $user->getGames()->getValues();

            if(in_array($game, $userGames)) {
                $hasIt = true;
            }
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
