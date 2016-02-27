<?php
namespace AppBundle\Command;

use AppBundle\Entity\ChallengePosition;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class ValidChallengeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('challenge:valid')
            ->setDescription('Valid a challenge')
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $helper = $this->getHelper('question');

        //GET USER
        $questionUser = new Question("<question>Please enter the id of the user:</question> ");
        $userId = $helper->ask($input, $output, $questionUser);
        if(!$userId) {
            $output->writeln("<error>You must give an ID</error>");
            return;
        }

        $user = $em->getRepository("AppBundle:User")->find($userId);

        if(!$user) {
            $output->writeln("<error>The user with the specific id: $userId, doesn't exist! Please check out for a new Id</error>");
            return;
        }
        //

        $challengeNumber = count($user->getChallenges());

        $username = $user->getUsername();
        $output->writeln("<comment>The selected user is: $username</comment>");
        $output->writeln("And he got: $challengeNumber active(s) challenge(s)");

        if($challengeNumber > 0) {
            foreach($user->getChallenges() as $key=>$challenge) {
                $text = $challenge->getName() . ", for game = " . $challenge->getGame()->getName();
                $output->writeln("<info>      $key : $text</info>");
                $output->writeln(" ");
            }

            $questionChallenge = new Question('<question>Please select the apropriate challenge:</question> ');

            $challengeId = $helper->ask($input, $output, $questionChallenge);
            $challenge = $user->getChallenges()[$challengeId];

            $already = $em->getRepository("AppBundle:ChallengePosition")->findOneBy(array("user" => $user, "challenge" => $challenge));
            if($already) {
                $output->writeln("<error>This player already got a position with this challenge which is : " . $already->getPosition() . "</error>");
                return;
            }
        } else {
            $output->writeln("The specific user doesn't have any active challenge");
            $output->writeln("Would you like to add one ?");

            $allChallenge = $em->getRepository("AppBundle:Challenge")->findAll();

            foreach($allChallenge as $key=>$challenge) {
                $text = $challenge->getName() . ", for game = " . $challenge->getGame()->getName();
                $output->writeln("<info>      $key : $text</info>");
                $output->writeln(" ");
            }

            $questionChallenge = new Question('<question>Please select the apropriate challenge:</question> ');

            $challengeId = $helper->ask($input, $output, $questionChallenge);
            $challenge = $allChallenge[$challengeId];

            $user->addChallenge($challenge);
            $challenge->addPlayer($user);
        }

        $questionPosition = new Question("<question>Please enter the position of the user in this challenge:</question> ");
        $positionNb = $helper->ask($input, $output, $questionPosition);

        $position = new ChallengePosition();
        $position->setPosition($positionNb);
        $position->setUser($user);
        $position->setChallenge($challenge);

        $limits = $challenge->getLimits();

        foreach($limits as $limit) {
            if($limit->getEnd() >= $positionNb && $limit->getBegin() <= $positionNb) {
                $awards = $limit->getAwards();
            }
        }

        if(!$awards) {
            $output->writeln("<error>No award associated with this position !</error>");
            return;
        }

        foreach($awards as $award) {
            $userLastPoints = $user->getXp();
            $output->writeln("Former xp = " . $userLastPoints);
            $output->writeln("--> Add xp = " . $award->getPoints());
            $output->writeln("New xp = " . ($userLastPoints + $award->getPoints()));
            $user->setXp($userLastPoints + $award->getPoints());
            if($game = $award->getGame()) {
                $output->writeln("-> Add this game = ". $award->getGame()->getName());

                if(!in_array($game, $user->getGames()->getValues())) {
                    $user->addGame($game);
                }
            }
            if($badge = $award->getBadge()) {
                $output->writeln("-> Add this badge = ". $award->getBadge()->getName());

                if(!in_array($badge, $user->getBadges()->getValues())) {
                    $user->addBadge($badge);
                }
            }
            $position->addAward($award);
        }

        $em->persist($position);
        $em->flush();
        $output->writeln("Success");
        return;
    }
}
