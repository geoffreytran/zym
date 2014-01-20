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

class FailedJob
{
    protected $id;
    protected $failedAt;
    protected $payload;
    protected $exception;
    protected $error;
    protected $backtrace;
    protected $worker;
    protected $queue;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFailedAt()
    {
        return $this->failedAt;
    }

    public function setFailedAt($failedAt)
    {
        $this->failedAt = $failedAt;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException($exception)
    {
        $this->exception = $exception;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    public function getBacktrace()
    {
        return $this->backtrace;
    }

    public function setBacktrace($backtrace)
    {
        $this->backtrace = $backtrace;
    }

    public function getWorker()
    {
        return $this->worker;
    }

    public function setWorker($worker)
    {
        $this->worker = $worker;
    }

    public function getQueue()
    {
        return $this->queue;
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;
    }
}