<?php

/**
 * Zym Framework
 *
 * This file is part of the Zym package.
 *
 * @link      https://github.com/geoffreytran/zym for the canonical source repository
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3 License
 */

namespace Zym\Bundle\ResqueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    /**
     * Whether the worker child has been signaled
     *
     * @var boolean
     */
    public $signaled;

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

        $env = array(
            'APP_INCLUDE'   => $this->getContainer()->getParameter('zym_resque.resque.vendor_dir') . '/autoload.php',
            'VVERBOSE'      => $input->getOption('verbose'),
            'QUEUE'         => $input->getArgument('queues'),
            'INTERVAL'      => (int)$input->getOption('interval'),
            'REDIS_BACKEND' => sprintf(
                '%s:%s',
                $container->getParameter('zym_resque.resque.redis.host'),
                $container->getParameter('zym_resque.resque.redis.port')
            )
        );

        $vendorDir = $container->getParameter('zym_resque.resque.vendor_dir');

        // Handle breaking changes in new version of php-resque
        if (file_exists($vendorDir . '/chrisboulton/php-resque/resque.php')) {
            $workerCommand = 'php ' . $vendorDir . '/chrisboulton/php-resque/resque.php';
        } else {
            $workerCommand = $vendorDir . '/chrisboulton/php-resque/bin/resque';
        }

        $process = new Process($workerCommand, $container->getParameter('kernel.root_dir'), $env, null, null);

        $process->start();

        if(function_exists('pcntl_signal')) {
            $worker = $this;
            $signalHandler = function($signal) use ($process, $output, $worker) {
                switch ($signal) {
                    case \SIGTERM:
                        $signalName = 'SIGTERM';
                        break;

                    case \SIGINT:
                        $signalName = 'SIGINT';
                        break;

                    case \SIGQUIT:
                        $signalName = 'SIGQUIT';
                        break;

                    case \SIGUSR1:
                        $signalName = 'SIGUSR1';
                        break;

                    case \SIGUSR2:
                        $signalName = 'SIGUSR2';
                        break;

                    case \SIGCONT:
                        $signalName = 'SIGCONT';
                        break;

                    case \SIGPIPE:
                        $signalName = 'SIGPIPE';
                        break;

                    default:
                        $signalName = $signal;
                }

                $output->writeln(sprintf('<error>%s signal caught</error>', $signalName));
                $worker->signaled = true;
                $process->signal($signal);
            };

            pcntl_signal(\SIGTERM, $signalHandler);
            pcntl_signal(\SIGINT, $signalHandler);
            pcntl_signal(\SIGQUIT, $signalHandler);
            pcntl_signal(\SIGUSR1, $signalHandler);
            pcntl_signal(\SIGUSR2, $signalHandler);
            pcntl_signal(\SIGCONT, $signalHandler);
            pcntl_signal(\SIGPIPE, $signalHandler);
        }

        $output->writeln(\sprintf('Starting worker <info>%s</info>', $process->getCommandLine()));
        $output->writeln('');

        try {
            $process->wait(function ($type, $buffer) use ($output) {
                // Color level
                $buffer = preg_replace('/^(\[info\])/', '<info>$1</info>', $buffer);
                $buffer = preg_replace('/^(\[debug\])/', '<fg=white>$1</fg=white>', $buffer);
                $buffer = preg_replace('/^(\[notice\])/', '<comment>$1</comment>', $buffer);
                $buffer = preg_replace('/^(\[warning\])/', '<error>$1</error>', $buffer);
                $buffer = preg_replace('/^(\[critical\])/', '<error>$1</error>', $buffer);


                // Color timestamp
                $buffer = preg_replace('/(\*\* \[\d{2}:\d{2}:\d{2} \d{4}-\d{2}-\d{2}\])/', '<comment>$1</comment>', $buffer);
                $buffer = preg_replace('/(\[\d{2}:\d{2}:\d{2} \d{4}-\d{2}-\d{2}\])/', '<comment>$1</comment>', $buffer);

                // Color
                $buffer = preg_replace('/\(Job(.*?)\|(.*?)\|(.*?)\|/', '(Job$1|$2|<info>$3</info>|', $buffer);

                $buffer = preg_replace('/Job{(.*?)}/', '<info>Job{</info><comment>$1</comment><info>}</info>', $buffer);
                $buffer = preg_replace('/(ID:)/', '<info>$1</info>', $buffer);

                // Color failed
                $buffer = preg_replace('/failed/', '<error>$1</error>', $buffer);

                $output->write($buffer);
            });
        } catch (\Symfony\Component\Process\Exception\RuntimeException $e) {
            if (!$this->signaled && !$process->getStopSignal() && !$process->getTermSignal()) {
                throw $e;
            }
        }

        $process->stop();

        $output->writeln('');
        $output->writeln('<info>Worker stopped...</info>');
    }
}
