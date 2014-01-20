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
 * Interface CDNInterface
 *
 * @package Zym\Bundle\MediaBundle\CDN
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
interface CDNInterface
{
    const STATUS_OK       = 1;
    const STATUS_TO_SEND  = 2;
    const STATUS_TO_FLUSH = 3;
    const STATUS_ERROR    = 4;
    const STATUS_WAITING  = 5;

    /**
     * Return the base path
     *
     * @param string  $relativePath
     * @param boolean $isFlushable
     *
     * @return string
     */
    public function getPath($relativePath, $isFlushable);

    /**
     * Flush a set of resources matching the provided string
     *
     * @param string $path
     *
     * @return void
     */
    public function flushPath($path);

    /**
     * Flush a set of resources matching the paths in provided array
     *
     * @param array $paths
     *
     * @return void
     */
    public function flushPaths(array $paths);
}