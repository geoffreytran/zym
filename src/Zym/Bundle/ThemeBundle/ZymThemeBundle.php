<?php

namespace Zym\Bundle\ThemeBundle;

use Zym\Bundle\ThemeBundle\DependencyInjection\Compiler\ThemeCompilerPass;
use Zym\Bundle\ThemeBundle\DependencyInjection\Compiler\TemplateResourcesPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
    }
}
