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

namespace Zym\Bundle\MediaBundle;

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Zym\Bundle\MediaBundle\Provider\MediaProviderInterface;

/**
 * Class MediaPool
 *
 * @package Zym\Bundle\MediaBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class MediaPool
{
    /**
     * Providers
     *
     * @var array
     */
    protected $providers = array();

    /**
     * Contexts
     *
     * @var array
     */
    protected $contexts = array();

    /**
     * @throws \RuntimeException
     *
     * @param string $name
     *
     * @return MediaProviderInterface
     */
    public function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            throw new \RuntimeException(sprintf('Unable to retrieve the provider named: "%s"', $name));
        }

        return $this->providers[$name];
    }

    /**
     * @param string                 $name
     * @param MediaProviderInterface $instance
     *
     * @return void
     */
    public function addProvider($name, MediaProviderInterface $instance)
    {
        $this->providers[$name] = $instance;
    }

    /**
     * @param string                                                 $name
     * @param \Sonata\MediaBundle\Security\DownloadStrategyInterface $security
     */
    public function addDownloadSecurity($name, DownloadStrategyInterface $security)
    {
        $this->downloadSecurities[$name] = $security;
    }

    /**
     * @param array $providers
     *
     * @return void
     */
    public function setProviders($providers)
    {
        $this->providers = $providers;
    }

    /**
     * @return MediaProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * @param string $name
     * @param array  $providers
     * @param array  $formats
     * @param array  $download
     *
     * @return void
     */
    public function addContext($name, array $providers = array(), array $formats = array(), array $download = array())
    {
        if (!$this->hasContext($name)) {
            $this->contexts[$name] = array(
                'providers' => array(),
                'formats'   => array(),
                'download'  => array(),
            );
        }

        $this->contexts[$name]['providers'] = $providers;
        $this->contexts[$name]['formats']   = $formats;
        $this->contexts[$name]['download']  = $download;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasContext($name)
    {
        return isset($this->contexts[$name]);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getContext($name)
    {
        if (!$this->hasContext($name)) {
            return null;
        }

        return $this->contexts[$name];
    }

    /**
     * Returns the context list
     *
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getProviderNamesByContext($name)
    {
        $context = $this->getContext($name);

        if (!$context) {
            return null;
        }

        return $context['providers'];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getFormatNamesByContext($name)
    {
        $context = $this->getContext($name);

        if (!$context) {
            return null;
        }

        return $context['formats'];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getProvidersByContext($name)
    {
        $providers = array();

        if (!$this->hasContext($name)) {
            return $providers;
        }

        foreach ($this->getProviderNamesByContext($name) as $name) {
            $providers[] = $this->getProvider($name);
        }

        return $providers;
    }

    /**
     * @return array
     */
    public function getProviderList()
    {
        $choices = array();
        foreach (array_keys($this->providers) as $name) {
            $choices[$name] = $name;
        }

        return $choices;
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return \Sonata\MediaBundle\Security\DownloadStrategyInterface
     */
    public function getDownloadSecurity(MediaInterface $media)
    {
        $context = $this->getContext($media->getContext());

        $id = $context['download']['strategy'];

        if (!isset($this->downloadSecurities[$id])) {
            throw new \RuntimeException('Unable to retrieve the download security : ' . $id);
        }

        return $this->downloadSecurities[$id];
    }

    /**
     * @param \Sonata\MediaBundle\Model\MediaInterface $media
     *
     * @return string
     */
    public function getDownloadMode(MediaInterface $media)
    {
        $context = $this->getContext($media->getContext());

        return $context['download']['mode'];
    }
}