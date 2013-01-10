<?php

namespace Zym\Bundle\GaufretteBundle\DependencyInjection\Factory;

use Knp\Bundle\GaufretteBundle\DependencyInjection\Factory\AdapterFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RackspaceCloudfilesAdapterFactory implements AdapterFactoryInterface
{
    /**
    * Creates the adapter, registers it and returns its id
    *
    * @param  ContainerBuilder $container  A ContainerBuilder instance
    * @param  string           $id         The id of the service
    * @param  array            $config     An array of configuration
    */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $containerDefinition = $container
            ->setDefinition($id . '.container', new DefinitionDecorator('zym_gaufrette.adapter.factory.rackspace_cloudfiles.container'))
            ->addArgument($config['container'])
            ->addArgument($config['user'])
            ->addArgument($config['api_key'])
            ->addArgument($config['service_net']);

        $definition = $container
            ->setDefinition($id, new DefinitionDecorator('zym_gaufrette.adapter.rackspace_cloudfiles'))
            ->addArgument(new Reference($id . '.container'));
    }

    /**
     * Returns the key for the factory configuration
     *
     * @return string
     */
    public function getKey()
    {
        return 'rackspace_cloudfiles';
    }

    /**
     * Adds configuration nodes for the factory
     *
     * @param  NodeBuilder $builder
     */
    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('service_net')->defaultValue(false)->end()
                ->scalarNode('container')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;
    }
}