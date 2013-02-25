<?php

namespace Zym\Bundle\RouterBundle;

use Zym\Bundle\RouterBundle\DependencyInjection\Compiler\RouterResourcePass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ZymRouterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RouterResourcePass());
    }
}
