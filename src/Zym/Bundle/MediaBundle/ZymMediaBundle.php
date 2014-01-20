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

namespace Zym\Bundle\MediaBundle;

use Zym\Bundle\MediaBundle\DependencyInjection\Compiler\AddProviderCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ZymMediaBundle
 *
 * @package Zym\Bundle\MediaBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
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
