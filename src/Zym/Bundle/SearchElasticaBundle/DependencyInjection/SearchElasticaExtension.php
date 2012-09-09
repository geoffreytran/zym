<?php

namespace Zym\Bundle\SearchElasticaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZymSearchElasticaExtension extends Extension
{
    protected $indexConfigs     = array();

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (empty($config['clients'])) {
            throw new InvalidArgumentException('You must define at least one client and one index');
        }

        if (empty($config['defaults']['client'])) {
            $keys = array_keys($config['clients']);
            $config['defaults']['client'] = reset($keys);
        }

        if (empty($config['defaults']['index'])) {
            $keys = array_keys($config['indexes']);
            $config['defaults']['index'] = reset($keys);
        }

        $clientIdsByName = $this->loadClients($config['clients'], $container);
        $indexIdsByName  = $this->loadIndexes($config['indexes'], $container, $clientIdsByName, $config['defaults']['client']);
        $indexRefsByName = array_map(function($id) {
            return new Reference($id);
        }, $indexIdsByName);

        $this->loadIndexManagers($indexRefsByName, $container);

        // Set default service alias
        $container->setAlias('zym_search_elastica.client', sprintf('zym_search_elastica.client.%s', $config['defaults']['client']));
        $container->setAlias('zym_search_elastica.index', sprintf('zym_search_elastica.index.%s', $config['defaults']['index']));
        $container->setAlias('zym_search_elastica.index_manager', sprintf('zym_search_elastica.index_manager.%s', $config['defaults']['index']));
    }

    /**
     * Loads the configured clients.
     *
     * @param array $config An array of clients configurations
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    protected function loadClients(array $clients, ContainerBuilder $container)
    {
        $clientIds = array();
        foreach ($clients as $name => $clientConfig) {
            $clientDef = $container->getDefinition('zym_search_elastica.client');
            $clientDef->replaceArgument(0, $clientConfig);

            $clientId = sprintf('zym_search_elastica.client.%s', $name);
            $container->setDefinition($clientId, $clientDef);

            $clientIds[$name] = $clientId;
        }

        return $clientIds;
    }

    /**
     * Loads the configured indexes.
     *
     * @param array $config An array of indexes configurations
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    protected function loadIndexes(array $indexes, ContainerBuilder $container, array $clientIdsByName, $defaultClientName)
    {
        $indexIds = array();
        foreach ($indexes as $name => $index) {

            if (isset($index['client'])) {
                $clientName = $index['client'];

                if (!isset($clientIdsByName[$clientName])) {
                    throw new InvalidArgumentException(sprintf('The elastica client with name "%s" is not defined', $clientName));
                }

            } else {
                $clientName = $defaultClientName;
            }

            $clientId     = $clientIdsByName[$clientName];
            $indexId      = sprintf('zym_search_elastica.index.%s', $name);
            $indexDefArgs = array($name);

            $indexDef = new Definition('%zym_search_elastica.index.class%', $indexDefArgs);
            $indexDef->setFactoryService($clientId);
            $indexDef->setFactoryMethod('getIndex');

            $container->setDefinition($indexId, $indexDef);

            $indexIds[$name] = $indexId;

            $this->indexConfigs[$name] = array(
                'index' => new Reference($indexId),
                'config' => array(
                )
            );

            if (!empty($index['settings'])) {
                $this->indexConfigs[$name]['config']['settings'] = $index['settings'];
            }
        }

        return $indexIds;
    }

    /**
     * Loads the index managers
     *
     * @param array            $indexRefsByName
     * @param ContainerBuilder $container
     **/
    protected function loadIndexManagers(array $indexRefsByName, ContainerBuilder $container)
    {
        foreach ($indexRefsByName as $name => $indexRef) {
            $managerId  = sprintf('zym_search_elastica.index_manager.%s', $name);
            $indexDefArgs = array($indexRef);

            $managerDef = new Definition('%zym_search_elastica.index_manager.class%', $indexDefArgs);
            $container->setDefinition($managerId, $managerDef);

        }
    }
}