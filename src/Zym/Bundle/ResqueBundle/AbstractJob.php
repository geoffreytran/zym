<?php

namespace Zym\Bundle\ResqueBundle;

abstract class AbstractJob
{
    /**
     * @var \Resque_Job
     */
    public $job;

    /**
     * @var string The queue name
     */
    public $queue = 'default';

    /**
     * @var array The job args
     */
    public $args = array();

    /**
     * Get the job name
     *
     * @return string
     */
    public function getName()
    {
        return \get_class($this);
    }

    /**
     * Setup the job
     *
     * @return void
     */
    public function setUp()
    {

    }

    /**
     * Perform the job
     *
     * @return void
     */
    public function perform()
    {
        $this->run($this->args);
    }

    /**
     * Run
     *
     * @return void
     */
    public abstract function run(array $args);

    /**
     * Tear down
     *
     * @return void
     */
    public function tearDown()
    {

    }
}
