<?php
namespace Zym\Bundle\ResqueBundle\Tests;

use Zym\Bundle\ResqueBundle\AbstractJob;

class ExceptionJob extends AbstractJob
{
    function __construct()
    {
        $this->args = array(
            'test' => rand(0, 10000),
            'test2' => rand(0, 10000),
            'test3' => rand(0, 10000),
        );
    }

    public function run(array $args)
    {
        throw new \Exception("Invalid division from 0 math");
    }
}