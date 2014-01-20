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

namespace Zym\Bundle\ThemeBundle;

use Zym\Bundle\ThemeBundle\DependencyInjection\Compiler\ThemeCompilerPass;
use Zym\Bundle\ThemeBundle\DependencyInjection\Compiler\TemplateResourcesPass;
use Zym\Bundle\ThemeBundle\DependencyInjection\Compiler\AddResolversPass;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ZymThemeBundle
 *
 * @package Zym\Bundle\ThemeBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ZymThemeBundle extends Bundle
{  
    /**
     * Builds the bundle.
     *
     * It is only ever called once when the cache is empty.
     *
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ThemeCompilerPass());
        $container->addCompilerPass(new TemplateResourcesPass());
        $container->addCompilerPass(new AddResolversPass());
    }
}
