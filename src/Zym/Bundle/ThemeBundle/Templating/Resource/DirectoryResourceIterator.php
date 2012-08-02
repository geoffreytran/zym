<?php

namespace Zym\Bundle\ThemeBundle\Templating\Resource;

use Symfony\Component\Templating\Loader\LoaderInterface;

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
