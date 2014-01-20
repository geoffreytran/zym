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

namespace Zym\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Zym\Bundle\ThemeBundle\DependencyInjection\DirectoryResourceDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\LogicException;

/**
 * Class TemplateResourcesPass
 *
 * @package Zym\Bundle\ThemeBundle\DependencyInjection\Compiler
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class TemplateResourcesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('assetic.asset_manager')) {
            return;
        }

        $engines = $container->getParameter('templating.engines');

        // Bundle and kernel resources
//        $bundles        = $container->getParameter('kernel.bundles');
//        $asseticBundles = $container->getParameterBag()->resolveValue($container->getParameter('assetic.bundles'));
//
//        foreach ($asseticBundles as $bundleName) {
//            $rc = new \ReflectionClass($bundles[$bundleName]);
//
//            foreach ($engines as $engine) {
//                $this->setBundleDirectoryResources($container, $engine, dirname($rc->getFileName()), $bundleName);
//            }
//        }

        foreach ($engines as $engine) {
            $this->setAppDirectoryResources($container, $engine);
        }
    }

    protected function setBundleDirectoryResources(ContainerBuilder $container, $engine, $bundleDirName, $bundleName)
    {
        if (!$container->hasDefinition('assetic.' . $engine . '_directory_resource.' . $bundleName)) {
            throw new LogicException('The ZymThemeBundle must be registered after the AsseticBundle in the application Kernel.');
        }

        $resources = $container->getDefinition('assetic.' . $engine . '_directory_resource.' . $bundleName)->getArgument(0);
        $themes    = $container->getParameter('zym_theme.themes');
        foreach ($themes as $theme) {
            $resources[] = new DirectoryResourceDefinition(
                $bundleName,
                $engine,
                array(
                    $bundleDirName . '/Resources/themes/' . $theme
                )
            );
        }

        $container->getDefinition('assetic.' . $engine . '_directory_resource.' . $bundleName)->replaceArgument(0, $resources);
    }

    protected function setAppDirectoryResources(ContainerBuilder $container, $engine)
    {
        if (!$container->hasDefinition('assetic.' . $engine . '_directory_resource.kernel')) {
            throw new LogicException('The ZymThemeBundle must be registered after the AsseticBundle in the application Kernel.');
        }

        $themes = $container->getParameter('zym_theme.themes');
        foreach ($themes as $key => $theme) {
            unset($themes[$key]);
            $themes[$theme] = $container->getParameter('kernel.root_dir') . '/Resources/themes/' . $theme;
        }

        $themes[''] = $container->getParameter('kernel.root_dir') . '/Resources/views';

        $container->setDefinition(
            'assetic.' . $engine . '_directory_resource.kernel',
            new DirectoryResourceDefinition('', $engine, $themes)
        );
    }
}
