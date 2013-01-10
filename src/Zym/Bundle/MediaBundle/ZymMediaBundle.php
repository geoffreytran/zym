<?php

namespace Zym\Bundle\MediaBundle;

use Zym\Bundle\MediaBundle\DependencyInjection\Compiler\AddProviderCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ZymMediaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddProviderCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // this is required by the AWS SDK (see: https://github.com/knplabs/Gaufrette)
        if (!defined("AWS_CERTIFICATE_AUTHORITY")) {
            define("AWS_CERTIFICATE_AUTHORITY", true);
        }
    }
}
