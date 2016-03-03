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
    private $session;

    public function __construct(NotificationService $notification, $notificationStatus, TokenStorage $token, Session $session)
    {
        $this->notification = $notification;
        $this->notificationStatus = $notificationStatus;
        $this->token = $token;
        $this->session = $session;
    }

    public function updateNotification(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->notificationStatus) {
            return;
        }

        if ($this->token->getToken()) {
            $user = $this->token->getToken()->getUser();

            if($user instanceof User) {
                $notifications = $this->notification->getNotifications($user->getId());

                foreach ($notifications as $notification) {
                    $this->session->getFlashBag()->add('notif', $notification);
                }
            }
        }
    }
}
