<?php

namespace Zym\Bundle\ResqueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zym:resque:worker')
            ->setDescription('Start a resque worker')
            ->addArgument('queues', InputArgument::OPTIONAL, 'Queue names (separate using comma).', '*')
            ->addOption('interval', 'i', InputOption::VALUE_OPTIONAL, 'Interval in seconds to check for jobs.', 5)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $container = $this->getContainer();

        /* @var $resque \Zym\Bundle\ResqueBundle\Resque */
        $resque    = $container->get('zym_resque.resque');

        $worker = new \Resque_Worker(explode(',', $input->getArgument('queues')));
	$worker->logLevel = ($input->getOption('verbose'))
                                ? \Resque_Worker::LOG_VERBOSE
                                : \Resque_Worker::LOG_NORMAL;

        $output->writeln(\sprintf('Starting worker <info>%s</info>', $worker));
	$worker->work($input->getOption('interval'));
    }
}
