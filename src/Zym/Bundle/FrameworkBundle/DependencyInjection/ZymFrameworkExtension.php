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

namespace Zym\Bundle\FrameworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\DefinitionDecorator,
    Symfony\Component\Config\FileLocator;


/**
 * Zym Framework Extension
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class ZymFrameworkExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array   $config        An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('zym_framework.form.rest_csrf.cookie.name',   $config['form']['rest_csrf']['cookie']['name']);
        $container->setParameter('zym_framework.form.rest_csrf.cookie.expire', $config['form']['rest_csrf']['cookie']['expire']);
        $container->setParameter('zym_framework.form.rest_csrf.cookie.path',   $config['form']['rest_csrf']['cookie']['path']);
        $container->setParameter('zym_framework.form.rest_csrf.cookie.domain', $config['form']['rest_csrf']['cookie']['domain']);
        $container->setParameter('zym_framework.form.rest_csrf.cookie.secure', $config['form']['rest_csrf']['cookie']['secure']);
        $container->setParameter('zym_framework.form.rest_csrf.header.name',   $config['form']['rest_csrf']['header']['name']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('orm.xml');
        $loader->load('form_csrf.xml');
    }
}