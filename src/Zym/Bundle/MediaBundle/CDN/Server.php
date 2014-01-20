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

/**
 * Class Server
 *
 * @package Zym\Bundle\MediaBundle\CDN
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class Server implements CDNInterface
{
    protected $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($relativePath, $isFlushable)
    {
        return sprintf('%s/%s', $this->path, $relativePath);
    }

    /**
     * {@inheritDoc}
     */
    public function flushPath($path)
    {
        // nothing to do
    }

    /**
     * {@inheritDoc}
     */
    public function flushPaths(array $paths)
    {
        // nothing to do
    }
}