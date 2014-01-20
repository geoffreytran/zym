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

use Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource as BaseDirectoryResource;
use Symfony\Component\Templating\Loader\LoaderInterface;

/**
 * Class DirectoryResource
 * A directory resource that creates Symfony2 templating resources.
 *
 * @package Zym\Bundle\ThemeBundle\Templating\Resource
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class DirectoryResource extends BaseDirectoryResource
{
    protected $theme;

    /**
     * Constructor.
     *
     * @param LoaderInterface $loader  The templating loader
     * @param string          $bundle  The current bundle name
     * @param string          $path    The directory path
     * @param string          $pattern A regex pattern for file basenames
     */
    public function __construct(LoaderInterface $loader, $bundle, $path, $pattern = null, $theme = null)
    {
        parent::__construct($loader, $bundle, $path, $pattern);

        $this->theme = $theme;
    }

    /**
     * @return \EmptyIterator|\Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResourceIterator|DirectoryResourceIterator
     */
    public function getIterator()
    {
        return is_dir($this->path)
            ? new DirectoryResourceIterator($this->loader, $this->bundle, $this->path, $this->getInnerIterator(), $this->theme)
            : new \EmptyIterator();
    }
}
