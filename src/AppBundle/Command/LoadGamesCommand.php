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
        $ct  = $this->getContainer();
        $cache = $ct->get('cache.service');
        $result = $cache->getEverythingFromCache();

        $em = $ct->get('doctrine')->getManager();
        $output->writeln("Start process...");
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $editors = $em->getRepository('AppBundle:Editor')->findAll();

        foreach($result as $game) {
            $return = $ct->get('load.game.service')->createGame($categories, $editors, $game);

            if($return['editor']) {
                $editors[] = $return['editor'];
                $em->persist($return['editor']);
            }
            foreach($return['categories'] as $category) {
                $categories[] = $category;
                $em->persist($category);
            }
            $em->persist($return['game']);
            $output->writeln("Game saved");
        }

        $em->flush();
        $output->writeln("Every games in cache have been load in database !");
        return;
    }
}
