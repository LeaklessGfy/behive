<?php
namespace AppBundle\Service;

class UserLevelService {
    public function updateLevels($user, $xp)
    {
        $newLevel = ($xp[1] / 100) + 1;
        $user->setLevel(floor($newLevel));
    }
}
