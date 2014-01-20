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

namespace Zym\Bundle\MediaBundle\Thumbnail;

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Zym\Bundle\MediaBundle\Provider\MediaProviderInterface;

interface ThumbnailInterface
{
    /**
     * @param MediaProviderInterface $provider
     * @param MediaInterface         $media
     * @param string                 $format
     */
    public function generatePublicUrl(MediaProviderInterface $provider, MediaInterface $media, $format);

    /**
     * @param MediaProviderInterface $provider
     * @param MediaInterface         $media
     * @param string                 $format
     */
    public function generatePrivateUrl(MediaProviderInterface $provider, MediaInterface $media, $format);

    /**
     * @param MediaProviderInterface $provider
     * @param MediaInterface         $media
     */
    public function generate(MediaProviderInterface $provider, MediaInterface $media);

    /**
     * @param MediaProviderInterface $provider
     * @param MediaInterface         $media
     */
    public function delete(MediaProviderInterface $provider, MediaInterface $media);
}