<?php

namespace Zym\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

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
