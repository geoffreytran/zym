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
        \Resque_Failure::setBackend('\Zym\Bundle\ResqueBundle\Failure\Redis');
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
