<?php
namespace AppBundle\Service;

class CreateGameService
{
    public function __construct($dir)
    {
        $this->uploadDir = $dir;
    }

    public function createGame($categories, $editors, $response)
    {
        //DEFINITIONS
        $gName = $response['name'];
        $gId = $response['id'];
        $gImage = $response['image']['medium_url'];
        $gDescription = $response['deck'];
        $gRating = $response['original_game_rating'];
        $gDate = $response['original_release_date'];
        $gGenres = $response['genres'];
        $gPublisher = $response['publishers'][0]['name'];

        $categoriesArray = array();
        foreach($categories as $category) {
            $categoriesArray[] = $category->getName();
        }

        $editorsArray = array();
        foreach($editors as $editor) {
            $editorsArray[] = $editor->getName();
        }

        //CREATE GAME OBJECT
        $game = new \AppBundle\Entity\Game();
        $game->setName($gName);
        $game->setDescription($gDescription);

        //DATE
        $date = $gDate;
        $date = new \DateTime($date);
        $game->setDate($date);

        //IMAGE
        $filename = "api-".$gId."-img.jpg";
        $dir = $this->uploadDir."game"."/".$filename;
        copy($gImage, $dir);
        $game->setCover("uploads/game/$filename");

        //PEGI
        if($gRating){
            foreach($gRating as $rating) {
                if(strpos($rating['name'], "PEGI:") !== false) {
                    preg_match_all('!\d+!', $rating['name'], $matches);
                    $game->setPegi($matches[0][0]);
                    break;
                }
            }
        }

        //CATEGORIES
        $categoriesToPersist = array();
        foreach($gGenres as $genre) {
            $id = array_search($genre["name"], $categoriesArray);
            if($id === false) {
                $newCategory = new \AppBundle\Entity\Category();
                $newCategory->setName($genre["name"]);

                $game->addCategory($newCategory);
                $categoriesToPersist[] = $newCategory;
            } else {
                if(!in_array($categories[$id], $game->getCategories()->getValues())) {
                    $game->addCategory($categories[$id]);
                }
            }
        }

        //EDITOR
        $newEditor = false;
        $id = array_search($gPublisher, $editorsArray);
        if($id === false) {
            $newEditor = new \AppBundle\Entity\Editor();
            $newEditor->setName($gPublisher);

            $game->setEditor($newEditor);
        } else {
            $game->setEditor($editors[$id]);
        }

        return array("game" => $game, "categories" => $categoriesToPersist, "editor" => $newEditor);
    }
}
