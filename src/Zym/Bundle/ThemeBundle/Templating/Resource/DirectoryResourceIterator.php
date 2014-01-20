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

namespace Zym\Bundle\ThemeBundle\Templating\Resource;

use Symfony\Component\Templating\Loader\LoaderInterface;

/**
 * Class DirectoryResourceIterator
 *
 * @package Zym\Bundle\ThemeBundle\Templating\Resource
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class DirectoryResourceIterator extends \RecursiveIteratorIterator
{
    protected $loader;
    protected $bundle;
    protected $path;
    protected $theme;

    /**
     * Constructor.
     *
     * @param LoaderInterface   $loader   The templating loader
     * @param string            $bundle   The current bundle name
     * @param string            $path     The directory
     * @param RecursiveIterator $iterator The inner iterator
     * @param string            $theme    The theme
     */
    public function __construct(LoaderInterface $loader, $bundle, $path, \RecursiveIterator $iterator, $theme = null)
    {
    	$this->loader = $loader;
    	$this->bundle = $bundle;
    	$this->path   = $path;
    	$this->theme  = $theme;

    	parent::__construct($iterator);
    }

    public function current()
    {
    	$file = parent::current();

    	return new FileResource($this->loader, $this->bundle, $this->path, $file->getPathname(), $this->theme);
    }
}
