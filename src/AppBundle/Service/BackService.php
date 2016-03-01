<?php
namespace AppBundle\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class BackService
{
    private $encoder;
    private $dir;

    public function __construct(UserPasswordEncoder $encoder, $dir)
    {
        $this->encoder = $encoder;
        $this->dir = $dir;
    }

    public function getRessourceName($ressource)
    {
        $nameHelp = array(
            "user" => "utilisateur",
            "game" => "jeux vidéo",
            "editor" => "éditeur",
            "challenge" => "challenge",
            "badge" => "badge",
            "category" => "categorie",
            "challengeAward" => "récompense",
            "challengePosition" => "position",
            "rolesCustom" => "role"
        );

        if(!isset($nameHelp[$ressource])) {
            return false;
        }

        return $nameHelp[$ressource];
    }

    public function getNewEntity($ressource)
    {
        $ressource = ucfirst($ressource);
        $entity= "AppBundle\\Entity\\".$ressource;

        return new $entity();
    }

    public function getForm($ressource)
    {
        $ressource = ucfirst($ressource);

        return "AppBundle\\Form\\Type\\".$ressource."Type";
    }

    public function handleImage($entity, $ressource, $image)
    {
        if($hasImage = $entity->hasImage()) {
            $file = $hasImage["get"];

            if($file) {
                $fileName = $ressource."-".time()."-img.jpg";
                $fileDir = $this->dir.$ressource;
                $file->move($fileDir, $fileName);

                $entity->$hasImage["set"]("uploads/".$ressource."/".$fileName);
            } else {
                $entity->$hasImage["set"]($image);
            }
        }
    }

    public function handleUserPassword($ressource, $entity)
    {
        if($ressource === "user") {
            $password = $this->encoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($password);
        }
    }
}
