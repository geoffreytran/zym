<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */

namespace Zym\Bundle\UserBundle\Tests\DependencyInjection;

use Zym\Bundle\UserBundle,
    Symfony\Component\Config\Definition\Processor;

/**
 * Zym User Bundle
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
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