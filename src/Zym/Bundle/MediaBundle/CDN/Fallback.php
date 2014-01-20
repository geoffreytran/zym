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
 * Class Fallback
 *
 * @package Zym\Bundle\MediaBundle\CDN
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class Fallback implements CDNInterface
{
    protected $cdn;

    protected $fallback;

    /**
     * @param CDNInterface $cdn
     * @param CDNInterface $fallback
     */
    public function __construct(CDNInterface $cdn, CDNInterface $fallback)
    {
        $this->cdn      = $cdn;
        $this->fallback = $fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($relativePath, $isFlushable)
    {
        if ($isFlushable) {
            return $this->fallback->getPath($relativePath, $isFlushable);
        }

        return $this->cdn->getPath($relativePath, $isFlushable);
    }

    /**
     * {@inheritdoc}
     */
    public function flushPath($path)
    {
        $this->cdn->flushPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function flushPaths(array $paths)
    {
        $this->cdn->flushPaths($paths);
    }
}