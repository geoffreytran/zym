<?php

namespace Zym\Bundle\ThemeBundle\Templating\Resource;

use Zym\Bundle\ThemeBundle\Templating\TemplateReference;
use Symfony\Bundle\AsseticBundle\Factory\Resource\FileResource as BaseFileResource;
use Assetic\Factory\Resource\ResourceInterface;
use Symfony\Component\Templating\Loader\LoaderInterface;

/**
 * A file resource.
 *
 */
class FileResource extends BaseFileResource
{
	protected $theme;
	
	/**
     * Constructor.
     *
     * @param LoaderInterface $loader  The templating loader
     * @param string          $bundle  The current bundle name
     * @param string          $baseDir The directory
     * @param string          $path    The file path
     * @param string          $theme   The theme
     */
    public function __construct(LoaderInterface $loader, $bundle, $baseDir, $path, $theme)
    {
	    parent::__construct($loader, $bundle, $baseDir, $path);
	    $this->theme = $theme;
	}
	
	public function getContent()
	{
		$templateReference = $this->getTemplate();
		$fileResource      = $this->loader->load($templateReference);

		if (!$fileResource) {
			throw new \InvalidArgumentException(sprintf('Unable to find template "%s".', $templateReference));
		}

		return $fileResource->getContent();
	}

	public function __toString()
	{
		return (string) $this->getTemplate();
	}

	protected function getTemplate()
	{
		if (null === $this->template) {
			$this->template = self::createTemplateReference($this->bundle, substr($this->path, strlen($this->baseDir)), $this->theme);
		}

		return $this->template;
	}

	static private function createTemplateReference($bundle, $file, $theme = null)
	{
		$parts = explode('/', strtr($file, '\\', '/'));
		$elements = explode('.', array_pop($parts));
		$engine = array_pop($elements);
		$format = array_pop($elements);
		$name = implode('.', $elements);

		$tr =  new TemplateReference($bundle, implode('/', $parts), $name, $format, $engine);
		$tr->set('theme', $theme);
		return $tr;
	}
}
