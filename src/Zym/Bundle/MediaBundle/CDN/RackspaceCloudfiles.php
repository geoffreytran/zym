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

namespace Zym\Bundle\MediaBundle\CDN;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \CF_Container as RackspaceContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RackspaceCloudfiles
 *
 * @package Zym\Bundle\MediaBundle\CDN
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RackspaceCloudfiles implements CDNInterface
{
    /**
     * Container
     *
     * @var RackspaceContainer
     */
    protected $container;

    /**
     * Request
     *
     * @var Request
     */
    protected $request;

    /**
     * @param string $path
     */
    public function __construct(RackspaceContainer $container, ContainerInterface $serviceContainer)
    {
        $this->container = $container;

        if ($serviceContainer->hasScope('request') && $serviceContainer->isScopeActive('request') && $serviceContainer->has('request')) {
            $this->request   = $serviceContainer->get('request');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($relativePath, $isFlushable)
    {
        $container = $this->container;
        $object    = $container->get_object($relativePath);

        if ($this->request && $this->request->isSecure()) {
            return $object->public_ssl_uri();
        } else {
            return $object->public_uri();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function flushPath($path)
    {
        $this->flushPaths(array($path));
    }

    /**
     * {@inheritDoc}
     */
    public function flushPaths(array $paths)
    {
        $container = $this->container;

        foreach ($paths as $relativePath) {
            $object = $container->get_object($relativePath);
            $object->purge_from_cdn();
        }
    }
}