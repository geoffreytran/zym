<?php

namespace Zym\Bundle\ResqueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Process\Process;
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
            ->addArgument('queues', InputArgument::OPTIONAL, 'Queue names (separate using comma)', '*')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);

        $container = $this->getContainer();
        $job = new \Zym\Bundle\ResqueBundle\TestJob();
        $container->get('zym_resque.resque')->enqueue($job);

        $env = array(
            'APP_INCLUDE'   => $this->getContainer()->getParameter('zym_resque.resque.vendor_dir') . '/autoload.php',
            'VVERBOSE'      => $input->getOption('verbose'),
            'QUEUE'         => $input->getArgument('queues'),
            'REDIS_BACKEND' => sprintf(
                '%s:%s',
                $container->getParameter('zym_resque.resque.redis.host'),
                $container->getParameter('zym_resque.resque.redis.port')
            )
        );

        // Handle breaking changes in new version of php-resque
        if (file_exists($this->getContainer()->getParameter('zym_resque.resque.vendor_dir') . '/chrisboulton/php-resque/resque.php')) {
            $workerCommand = 'php ' . $this->getContainer()->getParameter('zym_resque.resque.vendor_dir') . '/chrisboulton/php-resque/resque.php';
        } else {
            $workerCommand = $this->getContainer()->getParameter('zym_resque.resque.vendor_dir') . '/chrisboulton/php-resque/bin/resque';
        }

        $process = new Process($workerCommand, null, $env);

        $output->writeln(\sprintf('Starting worker <info>%s</info>', $process->getCommandLine()));

        $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });
    }
}
