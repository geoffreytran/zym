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

namespace Zym\Bundle\ResqueBundle;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareJob extends AbstractJob
{
    /**
     * @var KernelInterface
     */
    private $kernel = null;

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        if ($this->kernel === null) {
            $this->kernel = $this->createKernel();

            // We can't load the class cache because Symfony2 uses
            // Psr Logger which php-resque does too. Symfony2 will load all classes
            // without checking causing conflicts.
            if (method_exists($this->kernel, 'loadClassCache')) {
                $refObject   = new \ReflectionObject($this->kernel);
                $refProperty = $refObject->getProperty('loadClassCache');
                $refProperty->setAccessible(true);
                $refProperty->setValue($this->kernel, null);
            }

            $this->kernel->boot();
        }

        return $this->kernel->getContainer();
    }

    public function setKernelOptions(array $kernelOptions)
    {
        $this->args = \array_merge($this->args, $kernelOptions);
    }

    /**
     * @return KernelInterface
     */
    protected function createKernel()
    {
        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($this->args['kernel.root_dir']);

        $results = iterator_to_array($finder);
        $file    = current($results);
        $class   = $file->getBasename('.php');

        require_once $file;

        return new $class(
            isset($this->args['kernel.environment']) ? $this->args['kernel.environment'] : 'prod',
            isset($this->args['kernel.debug'])       ? $this->args['kernel.debug'] : false
        );
    }

    public function tearDown()
    {
        if ($this->kernel) {
            $this->kernel->shutdown();
        }
    }
}
