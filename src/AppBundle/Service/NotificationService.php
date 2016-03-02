<?php
namespace AppBundle\Service;

use Predis\Client as Redis;

class NotificationService {
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function getNotifications($userId)
    {
        $notifications = $this->redis->keys($userId."_notif-*");

        return $notifications;
    }
}
