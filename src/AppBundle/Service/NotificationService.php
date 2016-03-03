<?php
namespace AppBundle\Service;

use Predis\Client as Redis;

class NotificationService {
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
        $this->notificationsPanel = array(
            "new-user", "add-game", "earn-points", "earn-game", "earn-badge", "subscribe-challenge"
        );
    }

    public function getNotifications($userId)
    {
        $notifications = $this->redis->keys($userId."_notif-*");

        return $notifications;
    }

    public function setNotification($userId, $key, $value)
    {
        if(!in_array($key, $this->notificationsPanel)) {
            return;
        }

        $notification = $userId . "_" . $key;
        $this->redis->set($notification, $value);
    }
}
