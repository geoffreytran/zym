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

class Queue
{
    private $name;

    function __construct($name)
    {
        $this->name = $name;
    }

    function getSize()
    {
        return \Resque::size($this->name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getJobs()
    {
        $jobs = \Resque::redis()->lrange('queue:' . $this->name, -100, 100);

        $result = array();
        foreach ($jobs as $job) {
            $job = new \Resque_Job($this->name, \json_decode($job, true));
            $result[] = $job->getInstance();
        }

        return $result;
    }
}
