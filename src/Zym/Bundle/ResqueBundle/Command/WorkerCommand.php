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
            'INTERVAL'      => $input->getOption('interval'),
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

        $process = new Process($workerCommand, null, $env, null, null);

        $process->start();

        if(function_exists('pcntl_signal')) {
            // Get the process pid
            $reflectionClass    = new \ReflectionClass('Symfony\Component\Process\Process');
            $reflectionProperty = $reflectionClass->getProperty('process');
            $reflectionProperty->setAccessible(true);
            $processPid = $reflectionProperty->getValue($process);

            $status = proc_get_status($processPid);
            posix_setpgid($status['pid'], $status['pid']);

            declare(ticks = 1);

            $worker = $this;
            $signalHandler = function($signal) use ($status, $output, $worker) {
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
                posix_kill(-$status['pid'], $signal);
                //proc_terminate($processPid, $signal);
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

        try {
            $process->wait(function ($type, $buffer) use ($output) {
                // Color timestamp
                $buffer = preg_replace('/(\*\* \[\d{2}:\d{2}:\d{2} \d{4}-\d{2}-\d{2}\])/', '<comment>$1</comment>', $buffer);

                // Color
                $buffer = preg_replace('/\(Job(.*?)\|(.*?)\|(.*?)\|/', '(Job$1|$2|<info>$3</info>|', $buffer);

                $buffer = preg_replace('/Job{(.*?)}/', '<info>Job{</info><comment>$1</comment><info>}</info>', $buffer);
                $buffer = preg_replace('/(ID:)/', '<info>$1</info>', $buffer);

                // Color failed
                $buffer = preg_replace('/failed/', '<error>$1</error>', $buffer);

                $output->write($buffer);
            });
        } catch (\Symfony\Component\Process\Exception\RuntimeException $e) {
            if (!$this->signaled) {
                throw $e;
            }
        }

        $process->stop();
    }
}
