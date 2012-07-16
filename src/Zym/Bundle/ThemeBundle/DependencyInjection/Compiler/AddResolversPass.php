<?php

namespace Zym\Bundle\ThemeBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass registers the renderers in the RendererProvider.
 *
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
