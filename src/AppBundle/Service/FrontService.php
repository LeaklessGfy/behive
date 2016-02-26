<?php
namespace AppBundle\Service;

class FrontService
{
    public function getMainCategories($categories)
    {
        $action = null;
        $fps = null;
        $rpg= null;
        foreach($categories as $category) {
            if($category->getName() === "Action-Adventure") {
                $action = $category;
            } elseif($category->getName() === "First-Person Shooter") {
                $fps = $category;
            } elseif($category->getName() === "Role-Playing") {
                $rpg = $category;
            }
        }
        $mainCategories = array("action" => $action, "fps" => $fps, "rpg" => $rpg);

        return $mainCategories;
    }

    public function hasChallenge($user, $dailyChallenge, $challenges)
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
}
