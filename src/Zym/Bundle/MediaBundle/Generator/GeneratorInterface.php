<?php

namespace Zym\Bundle\MediaBundle\Generator;

use Zym\Bundle\MediaBundle\Model\MediaInterface;

interface GeneratorInterface
{
    /**
     * @abstract
     *
     * @param MediaInterface $media
     *
     * @return string
     */
    public function generatePath(MediaInterface $media);
}