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

namespace Zym\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass registers the renderers in the RendererProvider.
 *
 * @package Zym\Bundle\ThemeBundle\DependencyInjection\Compiler
 * @author Christophe Coevoet <stof@notk.org>
 */
class AddResolversPass implements CompilerPassInterface
{
	const THEME_MANAGER = 'zym_theme.theme_manager';
	
	/**
	 * You can modify the container here before it is dumped to PHP code.
	 *
	 * @param ContainerBuilder $container
	 *
	 * @api
	 */
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition(static::THEME_MANAGER)) {
			return;
		}
		
		$definition = $container->getDefinition(static::THEME_MANAGER);

		foreach ($container->findTaggedServiceIds('zym_theme.resolver') as $id => $tags) {
			$definition->addMethodCall('addResolver', array(new Reference($id)));
		}
	}
}
