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

namespace Zym\Bundle\MediaBundle\Resizer;

use Imagine\Image\ImagineInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Gaufrette\File;
use Zym\Bundle\MediaBundle\Model\MediaInterface;

/**
 * This reziser crop the image when the width and height are specified.
 * Every time you specify the W and H, the script generate a square with the
 * smaller size. For example, if width is 100 and height 80; the generated image
 * will be 80x80.
 *
 * @author Edwin Ibarra <edwines@feniaz.com>
 */
class SquareImageResizer implements ResizerInterface
{
    /**
     * ImagineInterface
     */
    protected $adapter;

    /**
     * string
     */
    protected $mode;

    /**
     * @param ImagineInterface $adapter
     * @param string           $mode
     */
    public function __construct(ImagineInterface $adapter, $mode)
    {
        $this->adapter = $adapter;
        $this->mode    = $mode;
    }

    /**
     * {@inheritdoc}
     */
    public function resize(MediaInterface $media, File $in, File $out, $format, array $settings)
    {
        if (!isset($settings['width'])) {
            throw new \RuntimeException(sprintf('Width parameter is missing in context "%s" for provider "%s"', $media->getContext(), $media->getProviderName()));
        }

        $image = $this->adapter->load($in->getContent());
        $size  = $media->getBox();

        if (null != $settings['height']) {
            if ($size->getHeight() > $size->getWidth()) {
                $higher = $size->getHeight();
                $lower  = $size->getWidth();
            } else {
                $higher = $size->getWidth();
                $lower  = $size->getHeight();
            }

            $crop = $higher - $lower;

            if ($crop > 0) {
                $point = $higher == $size->getHeight() ? new Point(0, 0) : new Point($crop / 2, 0);
                $image->crop($point, new Box($lower, $lower));
                $size = $image->getSize();
            }
        }

        $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());

        if ($settings['height'] < $size->getHeight() && $settings['width'] < $size->getWidth()) {
            $content = $image
                ->thumbnail(new Box($settings['width'], $settings['height']), $this->mode)
                ->get($format, array('quality' => $settings['quality']));
        } else {
            $content = $image->get($format, array('quality' => $settings['quality']));
        }

        $out->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function getBox(MediaInterface $media, array $settings)
    {
        $size = $media->getBox();

        if (null != $settings['height']) {

            if ($size->getHeight() > $size->getWidth()) {
                $higher = $size->getHeight();
                $lower  = $size->getWidth();
            } else {
                $higher = $size->getWidth();
                $lower  = $size->getHeight();
            }

            if ($higher - $lower > 0) {
                return new Box($lower, $lower);
            }
        }

        $settings['height'] = (int) ($settings['width'] * $size->getHeight() / $size->getWidth());

        if ($settings['height'] < $size->getHeight() && $settings['width'] < $size->getWidth()) {
            return new Box($settings['width'], $settings['height']);
        }

        return $size;
    }
}