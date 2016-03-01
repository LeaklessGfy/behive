<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiCallCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:igdb:call')
        ->setDescription('Call IGDB Api')
        ->addArgument(
            'offset',
            InputArgument::OPTIONAL,
            'Offset ?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //DEFINITIONS
        $ct  = $this->getContainer();
        $api = $ct->get('api.igdb');

        //DEFINE OFFSET AND ID
        $offset = $input->getArgument('offset');
        if(!$offset) {
            $offset = 1;
        }
        $id = "request-".$offset;

        $api->callApi($id, $offset);
    }
}
