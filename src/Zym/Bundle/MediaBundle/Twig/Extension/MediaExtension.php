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

namespace Zym\Bundle\MediaBundle\Twig\Extension;

use Zym\Bundle\MediaBundle\MediaPool;
use Zym\Bundle\MediaBundle\Model\MediaManagerInterface;
use Zym\Bundle\MediaBundle\Model\MediaInterface;

class MediaExtension extends \Twig_Extension
{
    protected $mediaPool;

    protected $resources = array();

    protected $mediaManager;

    protected $environment;

    /**
     * @param MediaPool $mediaPool
     * @param MediaManager $mediaManager
     */
    public function __construct(MediaPool $mediaPool, MediaManagerInterface $mediaManager)
    {
        $this->mediaPool    = $mediaPool;
        $this->mediaManager = $mediaManager;
    }
    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'media'           => new \Twig_Function_Method($this, 'media', array('is_safe' => array('html'))),
            'media_thumbnail' => new \Twig_Function_Method($this, 'thumbnail', array('is_safe' => array('html'))),
            'media_path'      => new \Twig_Function_Method($this, 'path'),
        );
    }

    /**
     * @param mixed $media
     *
     * @return null|\Sonata\MediaBundle\Model\MediaInterface
     */
    private function getMedia($media)
    {
        if (!$media instanceof MediaInterface && strlen($media) > 0) {
            $media = $this->mediaManager->findOneBy(array(
                'id' => $media
            ));
        }

        if (!$media instanceof MediaInterface) {
            return false;
        }

        if ($media->getProviderStatus() !== MediaInterface::STATUS_OK) {
            return false;
        }

        return $media;
    }

    /**
     * @param string $template
     * @param array  $parameters
     *
     * @return mixed
     */
    public function render($template, array $parameters = array())
    {
        if (!isset($this->resources[$template])) {
            $this->resources[$template] = $this->environment->loadTemplate($template);
        }

        return $this->resources[$template]->render($parameters);
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param string                                   $format
     * @param array                                    $options
     *
     * @return string
     */
    public function media($media = null, $format = 'reference', $options = array())
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this->mediaPool
                         ->getProvider($media->getProviderName());

        $format  = $provider->getFormatName($media, $format);
        $options = $provider->getHelperProperties($media, $format, $options);

        return $this->render($provider->getTemplate('view'), array(
            'media'    => $media,
            'format'   => $format,
            'options'  => $options,
        ));
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param string                                   $format
     *
     * @return string
     */
    public function path($media = null, $format)
    {
        $media = $this->getMedia($media);

        if (!$media) {
             return '';
        }

        $provider = $this->mediaPool->getProvider($media->getProviderName());
        $format   = $provider->getFormatName($media, $format);

        return $provider->generatePublicUrl($media, $format);
    }

    /**
     * Returns the thumbnail for the provided media
     *
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     * @param string                                   $format
     * @param array                                    $options
     *
     * @return string
     */
    public function thumbnail($media = null, $format, $options = array())
    {
        $media = $this->getMedia($media);

        if (!$media) {
            return '';
        }

        $provider = $this->mediaPool->getProvider($media->getProviderName());

        $format           = $provider->getFormatName($media, $format);
        $formatDefinition = $provider->getFormat($format);

        // build option
        $defaultOptions = array(
            'title' => $media->getName(),
        );

        if ($formatDefinition['width']) {
            $defaultOptions['width'] = $formatDefinition['width'];
        }
        if ($formatDefinition['height']) {
            $defaultOptions['height'] = $formatDefinition['height'];
        }

        $options = array_merge($defaultOptions, $options);

        $options['src'] = $provider->generatePublicUrl($media, $format);

        return $this->render($provider->getTemplate('thumbnail'), array(
            'media'    => $media,
            'options'  => $options,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zym_media';
    }

}