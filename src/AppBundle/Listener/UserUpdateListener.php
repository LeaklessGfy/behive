<?php
namespace AppBundle\Listener;

use AppBundle\Entity\User;
use AppBundle\Service\UserLevelService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserUpdateListener {
    private $userLevel;

    public function __construct(UserLevelService $userLevel)
    {
        $this->userLevel = $userLevel;
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            $em = $args->getEntityManager();
            $uow = $em->getUnitOfWork();
            $uow->computeChangeSets();
            $changeset = $uow->getEntityChangeSet($entity);

            if(isset($changeset['xp'])) {
                $this->userLevel->updateLevels($entity, $changeset['xp']);
            }
        }
    }
}
