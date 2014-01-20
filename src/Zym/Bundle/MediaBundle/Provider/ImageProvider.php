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

namespace Zym\Bundle\MediaBundle\Provider;

use Gaufrette\Filesystem;
use Zym\Bundle\MediaBundle\CDN\CDNInterface;
use Zym\Bundle\MediaBundle\Generator\GeneratorInterface;
use Zym\Bundle\MediaBundle\Thumbnail\ThumbnailInterface;
use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Imagine\Image\ImagineInterface;

use Symfony\Component\Form\FormBuilderInterface;

class ImageProvider extends FileProvider
{
    /**
     * Templates
     *
     * @var array
     */
    protected $templates = array(
        'thumbnail' => 'ZymMediaBundle:Provider:thumbnail.html.twig',
        'view'      => 'ZymMediaBundle:Provider:viewImage.html.twig'
    );

    protected $imagineAdapter;

    /**
     * @param string                                           $name
     * @param \Gaufrette\Filesystem                            $filesystem
     * @param CDNInterface                                     $cdn
     * @param GeneratorInterface                               $pathGenerator
     * @param ThumbnailInterface                               $thumbnail
     * @param array                                            $allowedExtensions
     * @param array                                            $allowedMimeTypes
     * @param \Imagine\Image\ImagineInterface                  $adapter
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail, array $allowedExtensions = array(), array $allowedMimeTypes = array(), ImagineInterface $adapter = null)
    {
        parent::__construct($name, $filesystem, $cdn, $pathGenerator, $thumbnail, $allowedExtensions, $allowedMimeTypes);

        $this->imagineAdapter = $adapter;
    }


    public function buildMediaType(FormBuilderInterface $builder)
    {
        $builder->add('binaryContent', 'file', array(
            'label' => 'Image File'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getHelperProperties(MediaInterface $media, $format, $options = array())
    {
        if ($format == 'reference') {
            $box = $media->getBox();
        } else {
            $resizerFormat = $this->getFormat($format);
            if ($resizerFormat === false) {
                throw new \RuntimeException(sprintf('The image format "%s" is not defined.
                        Is the format registered in your zym_media configuration?', $format));
            }

            $box = $this->resizer->getBox($media, $resizerFormat);
        }

        return array_merge(array(
            'title'    => $media->getName(),
            'src'      => $this->generatePublicUrl($media, $format),
            'width'    => $box->getWidth(),
            'height'   => $box->getHeight()
        ), $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function doTransform(MediaInterface $media)
    {
        parent::doTransform($media);

        if ($media->getBinaryContent()) {
            $image = $this->imagineAdapter->open($media->getBinaryContent()->getPathname());
            $size  = $image->getSize();

            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());

            $media->setProviderStatus(MediaInterface::STATUS_OK);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = true)
    {
        try {
            // this is now optimized at all!!!
            $path       = tempnam(sys_get_temp_dir(), 'zym_media_update_metadata');
            $fileObject = new \SplFileObject($path, 'w');
            $fileObject->fwrite($this->getReferenceFile($media)->getContent());

            $image = $this->imagineAdapter->open($fileObject->getPathname());
            $size  = $image->getSize();

            $media->setSize($fileObject->getSize());
            $media->setWidth($size->getWidth());
            $media->setHeight($size->getHeight());
        } catch (\LogicException $e) {
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            $media->setSize(0);
            $media->setWidth(0);
            $media->setHeight(0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generatePublicUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->thumbnail->generatePublicUrl($this, $media, $format);
        }

        return $this->getCdn()->getPath($path, $media->IsCdnFlushable());
    }

    /**
     * {@inheritdoc}
     */
    public function generatePrivateUrl(MediaInterface $media, $format)
    {
        if ($format == 'reference') {
            $path = $this->getReferenceImage($media);
        } else {
            $path = $this->thumbnail->generatePrivateUrl($this, $media, $format);
        }

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove(MediaInterface $media)
    {
        $path = $this->getReferenceImage($media);

        if ($this->getFilesystem()->has($path)) {
            $this->getFilesystem()->delete($path);
        }

        $this->thumbnail->delete($this, $media);
    }
}