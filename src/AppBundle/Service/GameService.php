<?php
namespace AppBundle\Service;

class GameService
{
    public function createGame($categories, $editor, $response)
    {
        $categoriesArray = array();
        foreach($categories as $key=>$category) {
            $categoriesArray[$key] = $category->getName();
        }

        $game = new \AppBundle\Entity\Game();
        $game->setName($response['name']);

        $filename = "api-".$response['id']."-img.jpg";
        $dir = __DIR__."/../../../web/uploads/game/";
        copy($response['image']['medium_url'], $dir.$filename);
        $game->setCover("uploads/game/$filename");

        $game->setDescription($response['deck']);
        $game->setRating(0);

        if($response['original_game_rating']){
            foreach($response['original_game_rating'] as $rating) {
                if(strpos($rating['name'], "PEGI:") !== false) {
                    preg_match_all('!\d+!', $rating['name'], $matches);
                    $game->setPegi($matches[0][0]);
                    break;
                }
            }
        }

        $date = $response['original_release_date'];
        $date = new \DateTime($date);
        $game->setDate($date);

        $categoriesToPersist = array();
        foreach($response['genres'] as $genre) {
            if(!$id = array_search($genre["name"], $categoriesArray)) {
                $newCategory = new \AppBundle\Entity\Category();
                $newCategory->setName($genre["name"]);

                $game->addCategory($newCategory);
                $categoriesToPersist[] = $newCategory;
            } else {
                $game->addCategory($categories[$id]);
            }
        }

        $publisher = $response['publishers'][0]['name'];

        $newEditor = false;
        if(!$editor) {
            $newEditor = new \AppBundle\Entity\Editor();
            $newEditor->setName($publisher);

            $game->setEditor($newEditor);
        } else {
            $game->setEditor($editor);
        }

        $return = array("game" => $game, "categories" => $categoriesToPersist, "editor" => $newEditor);

        return $return;
    }
}
