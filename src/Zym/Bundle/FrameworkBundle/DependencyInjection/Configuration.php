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

namespace Zym\Bundle\FrameworkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zym_framework');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('rest_csrf')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('header')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('name')->cannotBeEmpty()->defaultValue('X-XSRF-TOKEN')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('cookie')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('name')->cannotBeEmpty()->defaultValue('XSRF-TOKEN')->end()
                                        ->integerNode('expire')->cannotBeEmpty()->defaultValue(0)->end()
                                        ->scalarNode('path')->cannotBeEmpty()->defaultValue('/')->end()
                                        ->scalarNode('domain')->cannotBeEmpty()->defaultValue(null)->end()
                                        ->booleanNode('secure')->cannotBeEmpty()->defaultFalse()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
