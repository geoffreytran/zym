<?php

namespace Zym\Bundle\ThemeBundle\Templating;

use Symfony\Component\Templating\TemplateReference as BaseTemplateReference;

/**
 * Internal representation of a template.
 *
 * @author Victor Berchet <victor@suumit.com>
 */
class TemplateReference extends BaseTemplateReference
{
	public function __construct($bundle = null, $controller = null, $name = null, $format = null, $engine = null, $theme = null)
	{
		$this->parameters = array(
			'bundle'     => $bundle,
			'controller' => $controller,
			'name'       => $name,
			'format'     => $format,
			'engine'     => $engine,
			'theme'      => $theme,
		);
	}

	/**
	 * Returns the path to the template
	 *  - as a path when the template is not part of a bundle
	 *  - as a resource when the template is part of a bundle
	 *
	 * @return string A path to the template or a resource
	 */
	public function getPath()
	{
		$controller = str_replace('\\', '/', $this->get('controller'));

		$path = (empty($controller) ? '' : $controller.'/').$this->get('name').'.'.$this->get('format').'.'.$this->get('engine');

		return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/Resources/views/'.$path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLogicalName()
	{
		return sprintf('%s:%s:%s.%s.%s', $this->parameters['bundle'], $this->parameters['controller'], $this->parameters['name'], $this->parameters['format'], $this->parameters['engine']);
	}
}