<?php
namespace AppBundle\Listener;

use AppBundle\Entity\User;
use AppBundle\Service\NotificationService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class NotificationListener {
    private $notification;
    private $notificationStatus;

    public function __construct(NotificationService $notification, $notificationStatus, TokenStorage $token)
    {
        $this->notification = $notification;
        $this->notificationStatus = $notificationStatus;
        $this->token = $token;
    }

    public function updateNotification(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->notificationStatus) {
            return;
        }

        if ($this->token->getToken()) {
            $user = $this->token->getToken()->getUser();

            if($user instanceof User) {
                $this->notification->getNotifications($user->getId());
            }
        }
    }
}
