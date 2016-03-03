<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashHandlerService {
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function flashForNotifications($key, $value)
    {
        $def = array(
            "new-user" => "Bienvenue ".$value . " sur Behive ! Synchronisez votre compte Steam sur votre page profil !",
            "add-game" =>  $value . " à bien été ajouté à votre bibliothèque de jeu",
            "earn-points" => "Vous avez gagné " . $value . " points !",
            "earn-game" => "Vous avez remporté le jeu " . $value . ", téléchargez-le dés maintenant sur Steam !",
            "earn-badge" => "Vous avez remporté le badge " . $value . ", affichez-le au monde !",
            "challenge-subscribe" => "Vous vous êtes bien inscris au challenge " . $value . ", Que la chance force soit avec vous !",
            "challenge-complete" => "Vous êtes arrivé " . $value . ", au challenge : " . $value
        );

        $this->session->getFlashBag()->add('notif', $def[$key]);
    }
}
