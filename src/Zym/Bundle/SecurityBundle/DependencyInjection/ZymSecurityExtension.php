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

namespace Zym\Bundle\SecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZymSecurityExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('security_acl.xml');
        $loader->load('services.xml');
        
        $this->configureDbalAclProvider($config, $container, $loader);
    }
    
    private function configureDbalAclProvider(array $config, ContainerBuilder $container, $loader)
    {
        $loader->load('security_acl_dbal.xml');
    
        $connection = null;
        if ($container->hasAlias('security.acl.dbal.connection')) {
            $connection = $container->getParameter('security.acl.dbal.connection');
        }
        
        if ($connection === null && $container->hasParameter('doctrine.default_connection')) {
            $connection = $container->getParameter('doctrine.default_connection');
        }
    
        $container
            ->getDefinition('zym_security.acl.dbal.schema_listener')
            ->addTag('doctrine.event_listener', array(
                'connection' => $connection,
                'event'      => 'postGenerateSchemaTable',
                'lazy'       => true
            ))
        ;        
    }
}
