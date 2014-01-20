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

namespace Zym\Bundle\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZymMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('cdn.xml');
        $loader->load('filesystems.xml');
        $loader->load('providers.xml');
        $loader->load('resizers.xml');
        $loader->load('thumbnails.xml');
        $loader->load('twig.xml');

        if (!in_array(strtolower($config['db_driver']), array('orm', 'odm', 'phpcr'))) {
            throw new \InvalidArgumentException(sprintf('ZymMediaBundle - Invalid db driver "%s".', $config['db_driver']));
        }

        $loader->load(sprintf('%s.xml', $config['db_driver']));

        $pool = $container->getDefinition('zym_media.media_pool');

        // this shameless hack is done in order to have one clean configuration
        // for adding formats ....
        $pool->addMethodCall('__hack__', $config);

        $strategies = array();

        foreach ($config['contexts'] as $name => $settings) {
            $formats = array();

            foreach ($settings['formats'] as $format => $value) {
                $formats[$name.'_'.$format] = $value;
            }

            $strategies[] = $settings['download']['strategy'];
            $pool->addMethodCall('addContext', array($name, $settings['providers'], $formats, $settings['download']));
        }

        $strategies = array_unique($strategies);

        foreach ($strategies as $strategyId) {
            //$pool->addMethodCall('addDownloadSecurity', array($strategyId, new Reference($strategyId)));
        }

        $this->configureFilesystemAdapter($container, $config);
        $this->configureCdnAdapter($container, $config);

        $container->setParameter('zym_media.resizer.simple_image.adapter.mode', $config['resizer']['simple_image']['mode']);
        $container->setParameter('zym_media.resizer.square_image.adapter.mode', $config['resizer']['square_image']['mode']);

        $this->configureParameterClass($container, $config);
        $this->configureBuzz($container, $config);
        $this->configureProviders($container, $config);

    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureProviders(ContainerBuilder $container, $config)
    {
        $container->getDefinition('zym_media.provider.audio')
            ->replaceArgument(5, array_map('strtolower', $config['providers']['audio']['allowed_extensions']))
            ->replaceArgument(6, $config['providers']['audio']['allowed_mime_types'])
        ;

        $container->getDefinition('zym_media.provider.image')
            ->replaceArgument(5, array_map('strtolower', $config['providers']['image']['allowed_extensions']))
            ->replaceArgument(6, $config['providers']['image']['allowed_mime_types'])
            ->replaceArgument(7, new Reference($config['providers']['image']['adapter']))
        ;

        $container->getDefinition('zym_media.provider.file')
            ->replaceArgument(5, array_map('strtolower', $config['providers']['file']['allowed_extensions']))
            ->replaceArgument(6, $config['providers']['file']['allowed_mime_types'])
        ;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureBuzz(ContainerBuilder $container, array $config)
    {
        $container->getDefinition('zym_media.buzz.browser')
            ->replaceArgument(0, new Reference($config['buzz']['client']));
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     */
    public function configureParameterClass(ContainerBuilder $container, array $config)
    {
        $container->setParameter('zym_media.admin.media.entity', $config['class']['media']);
        $container->setParameter('zym_media.admin.gallery.entity', $config['class']['gallery']);
        $container->setParameter('zym_media.admin.gallery_has_media.entity', $config['class']['gallery_has_media']);

        $container->setParameter('zym_media.media.class', $config['class']['media']);
        $container->setParameter('zym_media.gallery.class', $config['class']['gallery']);

        $container->getDefinition('zym_media.form.type.media')->replaceArgument(1, $config['class']['media']);
    }

    /**
     * Inject CDN dependency to default provider
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     *
     * @return void
     */
    public function configureCdnAdapter(ContainerBuilder $container, array $config)
    {
        // add the default configuration for the server cdn
        if ($container->hasDefinition('zym_media.cdn.server') && isset($config['cdn']['server'])) {
            $container->getDefinition('zym_media.cdn.server')
                ->replaceArgument(0, $config['cdn']['server']['path'])
            ;
        } else {
            $container->removeDefinition('zym_media.cdn.server');
        }

        if ($container->hasDefinition('zym_media.cdn.fallback') && isset($config['cdn']['fallback'])) {
            $container->getDefinition('zym_media.cdn.fallback')
                ->replaceArgument(0, new Reference($config['cdn']['fallback']['master']))
                ->replaceArgument(1, new Reference($config['cdn']['fallback']['fallback']))
            ;
        } else {
            $container->removeDefinition('zym_media.cdn.fallback');
        }

        if ($container->hasDefinition('zym_media.cdn.rackspace_cloudfiles') && isset($config['cdn']['rackspace_cloudfiles'])) {
            $container->getDefinition('zym_media.cdn.rackspace_cloudfiles')
                ->replaceArgument(0, new Reference($config['cdn']['rackspace_cloudfiles']['service_id']))
            ;
        } else {
            $container->removeDefinition('zym_media.cdn.rackspace_cloudfiles');
        }
    }

    /**
     * Inject filesystem dependency to default provider
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array                                                   $config
     *
     * @return void
     */
    public function configureFilesystemAdapter(ContainerBuilder $container, array $config)
    {
        // add the default configuration for the local filesystem
        if ($container->hasDefinition('zym_media.adapter.filesystem.local') && isset($config['filesystem']['local'])) {
            $container->getDefinition('zym_media.adapter.filesystem.local')
                ->addArgument($config['filesystem']['local']['directory'])
                ->addArgument($config['filesystem']['local']['create'])
            ;
        } else {
            //$container->removeDefinition('zym_media.adapter.filesystem.local');
        }

        // add the default configuration for the FTP filesystem
        if ($container->hasDefinition('zym_media.adapter.filesystem.ftp') && isset($config['filesystem']['ftp'])) {
            $container->getDefinition('zym_media.adapter.filesystem.ftp')
                ->addArgument($config['filesystem']['ftp']['directory'])
                ->addArgument($config['filesystem']['ftp']['host'])
                ->addArgument($config['filesystem']['ftp']['username'])
                ->addArgument($config['filesystem']['ftp']['password'])
                ->addArgument($config['filesystem']['ftp']['port'])
                ->addArgument($config['filesystem']['ftp']['passive'])
                ->addArgument($config['filesystem']['ftp']['create'])
            ;
        } else {
            $container->removeDefinition('zym_media.adapter.filesystem.ftp');
            $container->removeDefinition('zym_media.filesystem.ftp');
        }

        // add the default configuration for the S3 filesystem
        if ($container->hasDefinition('zym_media.adapter.filesystem.s3') && isset($config['filesystem']['s3'])) {
            $container->getDefinition('zym_media.adapter.filesystem.s3')
                ->replaceArgument(0, new Reference('zym_media.adapter.service.s3'))
                ->replaceArgument(1, $config['filesystem']['s3']['bucket'])
                ->replaceArgument(2, array('create' => $config['filesystem']['s3']['create']))
                ->addMethodCall('setDirectory', array($config['filesystem']['s3']['directory']));
            ;

            $container->getDefinition('zym_media.adapter.service.s3')
                ->replaceArgument(0, array(
                    'secret' => $config['filesystem']['s3']['secretKey'],
                    'key'    => $config['filesystem']['s3']['accessKey'],
                ))
            ;
        } else {
            $container->removeDefinition('zym_media.adapter.filesystem.s3');
            $container->removeDefinition('zym_media.filesystem.s3');
        }

        if ($container->hasDefinition('zym_media.adapter.filesystem.replicate') && isset($config['filesystem']['replicate'])) {
            $container->getDefinition('zym_media.adapter.filesystem.replicate')
                ->replaceArgument(0, new Reference($config['filesystem']['replicate']['master']))
                ->replaceArgument(1, new Reference($config['filesystem']['replicate']['slave']))
            ;
        } else {
            $container->removeDefinition('zym_media.adapter.filesystem.replicate');
            $container->removeDefinition('zym_media.filesystem.replicate');
        }

        if ($container->hasDefinition('zym_media.adapter.filesystem.mogilefs') && isset($config['filesystem']['mogilefs'])) {
            $container->getDefinition('zym_media.adapter.filesystem.mogilefs')
                ->replaceArgument(0, $config['filesystem']['mogilefs']['domain'])
                ->replaceArgument(1, $config['filesystem']['mogilefs']['hosts'])
            ;
        } else {
            $container->removeDefinition('zym_media.adapter.filesystem.mogilefs');
            $container->removeDefinition('zym_media.filesystem.mogilefs');
        }
    }
}
