<?php
namespace AppBundle\Service;

class FrontService
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function hasChallenges($user, $dailyChallenge, $challenges)
    {
        $hasIt = array();
        if($user) {
            $playersForDC = $dailyChallenge->getPlayers();
            $dcIDS = $playersForDC->map(function($entity)  {
                return $entity->getId();
            })->toArray();

            if(in_array($user->getId(), $dcIDS)) {
                $hasIt[] = $dailyChallenge->getId();
            }

            foreach($challenges as $challenge) {
                $playersForC = $dailyChallenge->getPlayers();
                $cIDS = $playersForC->map(function($entity)  {
                    return $entity->getId();
                })->toArray();

                if(in_array($user->getId(), $cIDS)) {
                    $hasIt[] = $challenge['id'];
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

    public function handleAvatar($user, $avatar)
    {
        $file = $user->getAvatar();
        if($file) {
            $fileName = "user-".time()."-img.jpg";
            $fileDir = $this->uploadDir."user/";
            $file->move($fileDir, $fileName);

            $user->setAvatar("uploads/user/".$fileName);
        } else {
            $user->setAvatar($avatar);
        }
    }
}
