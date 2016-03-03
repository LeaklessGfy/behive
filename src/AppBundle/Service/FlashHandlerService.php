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
            "new-user" => "<h4>Bienvenue sur Behive!</h4><p>".$value ."Synchronisez votre compte Steam sur votre page profil !</p>",
            "add-game" => "<h4>Ajout de $value!</h4><p>Ajout d'un jeu à votre liste</p>",
            "earn-points" => "<h4>Plus d'expérience!</h4><p>Vous avez gagné " . $value . " points !</p>",
            "earn-game" => "<h4>Un nouveau jeu vous attend!</h4><p>Vous avez remporté le jeu " . $value . ", téléchargez-le dés maintenant sur Steam !</p>",
            "earn-badge" => "<h4>Un nouveau badge à votre collection!</h4><p>Vous avez remporté le badge " . $value . ", affichez-le au monde !</p>",
            "challenge-subscribe" => "<h4>Bonne chance!</h4><p>Vous vous êtes bien inscris au challenge " . $value . "</p>",
            "challenge-complete" => "<h4>Fin de challenge!</h4><p>Vous êtes arrivé " . $value . ", au challenge : " . $value . "</p>"
        );

        $this->session->getFlashBag()->add('notif', $def[$key]);
    }
}
