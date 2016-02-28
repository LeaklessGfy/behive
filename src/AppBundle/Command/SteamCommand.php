<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SteamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:steam')
        ->setDescription('Use IGDB Api');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //DEFINITIONS
        $ct  = $this->getContainer();
        $api = $ct->get('api.steam');
        $gameService = $ct->get('create.game.service');

        $games = $api->getUserGames("76561198079800787");

        foreach($games as $game) {
            $gameInfo = $api->getGameInfo($game->getAppId(), $game->getName());
            $game = $gameService->createGameFromSteam($gameInfo[$game->getAppId()]['data']);
            dump($game);
            //$ct->get('doctrine')->getManager()->flush();
        }
    }
}
