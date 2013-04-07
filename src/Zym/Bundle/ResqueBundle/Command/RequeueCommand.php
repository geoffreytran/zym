<?php

namespace Zym\Bundle\ResqueBundle\Command;

use Zym\Bundle\ResqueBundle\Failure\Redis;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RequeueCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('zym:resque:requeue')
            ->setDescription('Requeue a failed job')
            ->addArgument('job', InputArgument::OPTIONAL, 'Queue names (separate using comma).', '0')
            //->addArgument('queues', InputArgument::OPTIONAL, 'Queue names (separate using comma).', '*')
           // ->addOption('interval', 'i', InputOption::VALUE_OPTIONAL, 'Interval in seconds to check for jobs.', 5)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $container = $this->getContainer();

        /* @var $resque \Zym\Bundle\ResqueBundle\Resque */
        $resque    = $container->get('zym_resque.resque');

        for ($x=0; $x < 13831; $x++) {
                Redis::requeue($x);
        }
    }
}
