<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiUseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:igdb:use')
        ->setDescription('Use IGDB Api');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //DEFINITIONS
        $ct  = $this->getContainer();
        $api = $ct->get('cache.service');

        //GET CACHE DATA
        $results = $api->getEverythingFromCache("request-*.json", "ApiIgdb/");

        $gamesArray = array();
        foreach($results as $result) {
            $result = json_decode($result, true)['games'];

            foreach($result as $game) {
                $gamesArray[] = $game["name"];
            }
        }

        dump($gamesArray);
    }
}
