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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * Class ZymUserExtensionTest
 *
 * @package Zym\Bundle\UserBundle\Tests\DependencyInjection
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ZymUserExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var UserBundle\DependencyInjection\Configuration
     */
    protected $configuration;

    protected function tearDown()
    {
        unset($this->configuration);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testUserLoadThrowsExceptionUnlessDatabaseDriverSet()
    {
        $loader = new UserBundle\DependencyInjection\ZymUserExtension();
        $config = $this->getEmptyConfig();
        unset($config['db_driver']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUserLoadThrowsExceptionUnlessDatabaseDriverIsValid()
    {
        $loader = new UserBundle\DependencyInjection\ZymUserExtension();
        $config = $this->getEmptyConfig();
        $config['db_driver'] = 'foo';
        $loader->load(array($config), new ContainerBuilder());
    }

    public function testUserLoadDatabaseDriverIsValidORM()
    {
        $container = new ContainerBuilder();

        $loader = new UserBundle\DependencyInjection\ZymUserExtension();
        $config = $this->getEmptyConfig();
        $config['db_driver'] = 'orm';
        $loader->load(array($config), $container);

        $this->assertEquals($container->getParameter('fos_user.user_manager.class'),
                                'Zym\Bundle\UserBundle\Entity\UserManager');
    }

    public function testUserLoadDatabaseDriverIsValidMongo()
    {
        $container = new ContainerBuilder();

        $loader = new UserBundle\DependencyInjection\ZymUserExtension();
        $config = $this->getEmptyConfig();
        $config['db_driver'] = 'mongodb';
        $loader->load(array($config), $container);

        $this->assertEquals($container->getParameter('fos_user.user_manager.class'),
                                'Zym\Bundle\UserBundle\Document\UserManager');
    }


    /**
     * @return ContainerBuilder
     */
    protected function createEmptyConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new UserBundle\DependencyInjection\ZymUserExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * @return ContainerBuilder
     */
    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new UserBundle\DependencyInjection\ZymUserExtension();
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);
    }

    /**
     * getEmptyConfig
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
db_driver: mongodb
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    protected function getFullConfig()
    {
        $yaml = <<<EOF
db_driver: orm
EOF;
        $parser = new Parser();

        return  $parser->parse($yaml);
    }
}