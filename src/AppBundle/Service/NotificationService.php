<?php
namespace AppBundle\Service;

use Predis\Client as Redis;

class NotificationService {
    private $redis;
    private $flashHandler;
    private $notificationStatus;
    private $notificationsPanel;

    public function __construct(Redis $redis, FlashHandlerService $flashHandler, $notificationStatus)
    {
        $this->redis = $redis;
        $this->flashHandler = $flashHandler;
        $this->notificationStatus = $notificationStatus;
        $this->notificationsPanel = array(
            "new-user", "add-game", "earn-points", "earn-game", "earn-badge", "challenge-subscribe", "challenge-complete"
        );
    }

    public function getNotificationsKeys($userId)
    {
        $keys = $this->redis->keys($userId."_notif_*");

        return $keys;
    }

    public function getNotificationsValues($mget)
    {
        $values = $this->redis->mget($mget);

        return $values;
    }

    public function getNotifications($id)
    {
        $notificationsKeys = $this->getNotificationsKeys($id);

        dump($notificationsKeys);
        dump("dd");

        if(!$notificationsKeys) {
            return;
        }

        $notificationsValues = $this->getNotificationsValues($notificationsKeys);
        $notificationsLength = count($notificationsKeys);

        for($i = 0; $i < $notificationsLength; $i++) {
            $end = strpos($notificationsKeys[$i], "-") + 1;
            $key = substr($notificationsKeys[$i], $end);
            $value = $notificationsValues[$i];

            $this->flashHandler->flashForNotifications($key, $value);
        }

        $this->unsetKeys($notificationsKeys);
    }

    public function setNotification($userId, $key, $value)
    {
        if(!in_array($key, $this->notificationsPanel) || !$this->notificationStatus) {
            return;
        }

        $uniq = count($this->redis->keys($userId . "_notif_*-" . $key)) + 1;

        $notification = $userId . "_notif_" . $uniq . "-" . $key;
        $this->redis->set($notification, $value);
    }

    public function unsetKeys($keys)
    {
        $this->redis->del($keys);
    }
}
