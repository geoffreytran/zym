<?php

namespace Zym\Bundle\MediaBundle\Resizer;

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Gaufrette\File;

interface ResizerInterface
{
    /**
     * @param MediaInterface $media
     * @param File           $in
     * @param File           $out
     * @param string         $format
     * @param array          $settings
     *
     * @return void
     */
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings);

    /**
     * @param MediaInterface $media
     * @param array          $settings
     *
     * @return \Imagine\Image\Box
     */
    public function getBox(MediaInterface $media, array $settings);
}