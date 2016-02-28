<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadGamesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('games:load')
        ->setDescription('Load games from cache files');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //DEFINITIONS
        $ct  = $this->getContainer();
        $em = $ct->get('doctrine')->getManager();
        $cache = $ct->get('cache.service');

        //GET CACHE DATA
        $results = $cache->getEverythingFromCache("game-*.json", "ApiGiant");

        //GET FORMER CATEGORIES AND EDITORS
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $editors = $em->getRepository('AppBundle:Editor')->findAll();

        $output->writeln("Start process...");
        foreach($results as $game) {
            $game = json_decode($game, true)['results'];

            //CREATE GAME
            $return = $ct->get('load.game.service')->createGame($categories, $editors, $game);

            //PERSIST EDITOR
            if($return['editor']) {
                $editors[] = $return['editor'];
                $em->persist($return['editor']);
            }
            //PERSIST CATEGORIES
            foreach($return['categories'] as $category) {
                $categories[] = $category;
                $em->persist($category);
            }
            //PERSIST GAME
            $em->persist($return['game']);

            $output->writeln("Game saved");
        }

        //FINAL FLUSH
        $em->flush();
        $output->writeln("Every games in cache have been load in database !");

        return;
    }
}
