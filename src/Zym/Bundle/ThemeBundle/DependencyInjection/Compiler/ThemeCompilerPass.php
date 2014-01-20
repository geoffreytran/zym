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

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class ThemeCompilerPass
 *
 * @package Zym\Bundle\ThemeBundle\DependencyInjection\Compiler
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ThemeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->setAlias('templating.locator', 'zym_theme.templating_locator');

        $container->setAlias('templating.cache_warmer.template_paths', 'zym_theme.templating.cache_warmer.template_paths');
        //
        // if (!$container->getParameter('liip_theme.cache_warming')) {
        //     $container->getDefinition('liip_theme.templating.cache_warmer.template_paths')
        //         ->replaceArgument(2, null);
        // }
    }
}
