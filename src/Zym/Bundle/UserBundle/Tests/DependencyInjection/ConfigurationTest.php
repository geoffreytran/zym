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


namespace Zym\Bundle\UserBundle\Tests\DependencyInjection;

use Zym\Bundle\UserBundle;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class ConfigurationTest
 *
 * @package Zym\Bundle\UserBundle\Tests\DependencyInjection
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var UserBundle\DependencyInjection\Configuration
     */
    protected $configuration;

    protected function setUp()
    {
        $this->configuration = new UserBundle\DependencyInjection\Configuration();
    }

    protected function tearDown()
    {
        unset($this->configuration);
    }

    public function testGetConfigTreeBuilderHasRootNode()
    {
        $processor     = new Processor();
        $configuration = $this->configuration;

        $config        = $processor->processConfiguration(
            $configuration,
            array('zym_user' => array('db_driver' => 'orm')
        ));
        
        $this->assertArrayHasKey('db_driver', $config);
    }
}