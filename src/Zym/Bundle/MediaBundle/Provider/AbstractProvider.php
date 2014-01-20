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

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\AbstractType as FormAbstractType;
use Gaufrette\Filesystem;
use Zym\Bundle\MediaBundle\CDN\CDNInterface;
use Zym\Bundle\MediaBundle\Generator\GeneratorInterface;
use Zym\Bundle\MediaBundle\Thumbnail\ThumbnailInterface;
use Zym\Bundle\MediaBundle\Resizer\ResizerInterface;

abstract class AbstractProvider implements MediaProviderInterface
{
    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * Formats
     *
     * @var array
     */
    protected $formats = array();

    /**
     * Templates
     *
     * @var array
     */
    protected $templates = array();

    /**
     * Resizer
     *
     * @var ResizerInterface
     */
    protected $resizer;


    protected $filesystem;

    protected $pathGenerator;

    protected $cdn;

    protected $thumbnail;

    /**
     * Construct
     *
     * @param string                                           $name
     * @param \Gaufrette\Filesystem                            $filesystem
     * @param CDNInterface                                     $cdn
     * @param GeneratorInterface                               $pathGenerator
     * @param ThumbnailInterface                               $thumbnail
     */
    public function __construct($name, Filesystem $filesystem, CDNInterface $cdn, GeneratorInterface $pathGenerator, ThumbnailInterface $thumbnail)
    {
        $this->name          = $name;
        $this->filesystem    = $filesystem;
        $this->cdn           = $cdn;
        $this->pathGenerator = $pathGenerator;
        $this->thumbnail     = $thumbnail;
    }

    /**
     * @param MediaInterface $media
     *
     * @return void
     */
    abstract protected function doTransform(MediaInterface $media);

    /**
     * {@inheritdoc}
     */
    final public function transform(MediaInterface $media)
    {
        if (null === $media->getBinaryContent()) {
            return;
        }

        $this->doTransform($media);
    }

    /**
     * {@inheritdoc}
     */
    public function addFormat($name, $format)
    {
        $this->formats[$name] = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat($name)
    {
        return isset($this->formats[$name]) ? $this->formats[$name] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * {@inheritdoc}
     */
    public function requireThumbnails()
    {
        return $this->getResizer() !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function generateThumbnails(MediaInterface $media)
    {
        $this->thumbnail->generate($this, $media);
    }

    /**
     * {@inheritdoc}
     */
    public function removeThumbnails(MediaInterface $media)
    {
        $this->thumbnail->delete($this, $media);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatName(MediaInterface $media, $format)
    {
        if ($format == 'admin') {
            return 'admin';
        }

        if ($format == 'reference') {
            return 'reference';
        }

        $baseName = $media->getContext() . '_';
        if (substr($format, 0, strlen($baseName)) == $baseName) {
            return $format;
        }

        return $baseName.$format;
    }

    /**
     * {@inheritdoc}
     */
    public function postPersist(MediaInterface $media)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function postRemove(MediaInterface $media)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate(MediaInterface $media)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function prePersist(MediaInterface $media)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate(MediaInterface $media)
    {

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

    /**
     * {@inheritdoc}
     */
    public function generatePath(MediaInterface $media)
    {
        return $this->pathGenerator->generatePath($media);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate($name)
    {
        return isset($this->templates[$name]) ? $this->templates[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setResizer(ResizerInterface $resizer)
    {
        $this->resizer = $resizer;
    }

    /**
     * {@inheritdoc}
     */
    public function getResizer()
    {
        return $this->resizer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getCdn()
    {
        return $this->cdn;
    }

    /**
     * {@inheritdoc}
     */
    public function getCdnPath($relativePath, $isFlushable)
    {
        return $this->getCdn()->getPath($relativePath, $isFlushable);
    }
}