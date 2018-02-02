<?php

/**
 * Description of TaskCommand
 *
 * @author pinacolada
 */
namespace Bemoove\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class TaskTestMailCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('task:testMail')
            ->setDescription('Task command that send a test Mail')
            ->addArgument('destMail', InputArgument::REQUIRED, 'The mailbox to send to.')
            ->addArgument('template', InputArgument::OPTIONAL, 'Mail template to be send : basic, welcomecoach')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Running Task command...</comment>');

        $dest = $input->getArgument('destMail');
        $template = $input->getArgument('template');

        $output->writeln('<comment>Sending mails to : '.$dest.'</comment>');


        try {
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');

            if (!$template || $template === 'basic') {
                $output->write('<comment>Mail : basic = </comment>');
                $result = $this->getContainer()->get('mymail')->sendBasicEmail($dest);
                if($result) $output->writeln('<info>SUCCESS</info>');
                else $output->writeln('<error>ERROR!</error>');
            }

            if (!$template || $template === 'welcomecoach') {
                $output->write('<comment>Mail : welcome Coach = </comment>');
                $result = $this->getContainer()->get('mymail')->sendWelcomeCoachEmail($dest);
                if($result) $output->writeln('<info>SUCCESS</info>');
                else $output->writeln('<error>ERROR!</error>');
            }

            if (!$template || $template === 'welcomemember') {
                $output->write('<comment>Mail : welcome Member = </comment>');
                $result = $this->getContainer()->get('mymail')->sendWelcomeMemberEmail($dest);
                if($result) $output->writeln('<info>SUCCESS</info>');
                else $output->writeln('<error>ERROR!</error>');
            }

        } catch (\Exception $e) {
            $output->writeln('<error>ERROR</error>'.$e->getMessage());
        }

        $output->writeln('<comment>Task done!</comment>');
    }
}
