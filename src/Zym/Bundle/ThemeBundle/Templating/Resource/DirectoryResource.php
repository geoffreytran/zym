<?php

namespace Zym\Bundle\ThemeBundle\Templating\Resource;

use Symfony\Bundle\AsseticBundle\Factory\Resource\DirectoryResource as BaseDirectoryResource;
use Symfony\Component\Templating\Loader\LoaderInterface;

/**
 * A directory resource that creates Symfony2 templating resources.
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

    public function getIterator()
    {
        return is_dir($this->path)
            ? new DirectoryResourceIterator($this->loader, $this->bundle, $this->path, $this->getInnerIterator(), $this->theme)
            : new \EmptyIterator();
    }
}
