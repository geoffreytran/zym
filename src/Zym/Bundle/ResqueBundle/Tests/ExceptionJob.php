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