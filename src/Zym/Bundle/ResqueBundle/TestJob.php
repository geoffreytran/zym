<?php

namespace Zym\Bundle\ResqueBundle;
class TestJob extends Job
{
    public function __construct()
    {
        $this->queue = 'test';
    }

    public function run($args)
    {
        sleep(3);
        echo 'hi';
    }
}