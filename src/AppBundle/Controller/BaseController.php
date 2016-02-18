<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseController extends Controller
{
    private $apiKey;
    private $apiBaseUrl;
    private $apiVersion;
    private $apiId;

    public function __construct()
    {
        $this->apiKey = "EB2471058B905E6A6D6036A38C135015";
        $this->apiBaseUrl = " http://api.steampowered.com/";
        $this->apiVersion = "v0001";
        $this->apiId = "440";
    }

    protected function callSteamApi($interface, $method, $steamId)
    {
        $url = $this->apiBaseUrl . "/" . $interface . "/" . $method . "/" . $this->apiVersion . "/&key=" . $this->apiKey . "&steamid=" . $steamId;
    }

    protected function getPlayerAchievement($steamId)
    {
        $interface = "ISteamUserStats";
        $method = "GetPlayerAchievements";

        $response = $this->callSteamApi($interface, $method, $steamId);
    }

    protected function getRessourceName($ressource)
    {
        $nameHelp = array(
            "user" => "utilisateur",
            "game" => "jeux vidéo",
            "editor" => "éditeur",
            "challenge" => "challenge",
            "badge" => "badge",
            "category" => "categorie",
            "challengeAward" => "récompense",
            "rolesCustom" => "role"
        );

        if(!isset($nameHelp[$ressource])) {
            return false;
        }

        return $nameHelp[$ressource];
    }

    protected function checkIfExist($ressourceHelper)
    {
        if(!$ressourceHelper) {
            $this->addFlash("danger", "Cette ressource n'existe pas");
            throw new HttpException(307, null, null, array('Location' => $this->generateUrl('back_home')));
        }
    }

    protected function getNewEntity($ressource)
    {
        $ressource = ucfirst($ressource);

        $entity= "AppBundle\\Entity\\".$ressource;
        return new $entity();
    }

    protected function getForm($ressource)
    {
        $ressource = ucfirst($ressource);

        return "AppBundle\\Form\\Type\\".$ressource."Type";
    }

    protected function handleImage($entity, $ressource)
    {
        if($hasImage = $entity->hasImage()) {
            $file = $hasImage["get"];

            if($file) {
                $fileName = $ressource."-".time()."-img.jpg";
                $fileDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$ressource;
                $file->move($fileDir, $fileName);

                $entity->$hasImage["set"]("uploads/".$ressource."/".$fileName);
            }
        }
    }

    protected function handleUserPassword($ressource, $entity)
    {
        if($ressource === "User") {
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($password);
        }
    }

    protected function handleChallenge($ressource, $entity, $em)
    {
        if($ressource === "challenge") {
            $limits = $entity->getLimits();

            foreach($limits as $limit) {
                $limit->setChallenge($entity);
                $em->persist($limit);
            }
        }
    }

    protected function createGame($response)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $editor = $em->getRepository('AppBundle:Editor')->findOneBy(array('name' => $response['publishers'][0]['name']));

        $categoriesArray = array();
        foreach($categories as $key=>$category) {
            $categoriesArray[$key] = $category->getName();
        }

        $game = new \AppBundle\Entity\Game();
        $game->setName($response['name']);

        $filename = "api-".$response['id']."-img.jpg";
        copy($response['image']['super_url'], 'uploads/game/'.$filename);
        $game->setCover("uploads/game/$filename");

        $game->setDescription($response['deck']);
        $game->setRating(0);

        foreach($response['original_game_rating'] as $rating) {
            if(strpos($rating['name'], "PEGI:") !== false) {
                preg_match_all('!\d+!', $rating['name'], $matches);
                $game->setPegi($matches[0][0]);
                break;
            }
        }

        $date = $response['original_release_date'];
        $date = new \DateTime($date);
        $game->setDate($date);

        foreach($response['genres'] as $genre) {
            if(!$id = array_search($genre["name"], $categoriesArray)) {
                $newCategory = new \AppBundle\Entity\Category();
                $newCategory->setName($genre["name"]);

                $game->addCategory($newCategory);
                $em->persist($newCategory);
            } else {
                $game->addCategory($categories[$id]);
            }
        }

        $publisher = $response['publishers'][0]['name'];

        if(!$editor) {
            $newEditor = new \AppBundle\Entity\Editor();
            $newEditor->setName($publisher);

            $game->setEditor($newEditor);
            $em->persist($newEditor);
        } else {
            $game->setEditor($editor);
        }

        return $game;
    }
}