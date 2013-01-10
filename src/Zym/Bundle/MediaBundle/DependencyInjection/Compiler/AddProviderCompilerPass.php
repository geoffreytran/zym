<?php

namespace Zym\Bundle\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AddProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $settings = $this->fixSettings($container);

        // define configuration per provider
        $this->applyFormats($container, $settings);
        $this->attachArguments($container, $settings);
        $this->attachProviders($container);

        foreach ($container->findTaggedServiceIds('zym_media.provider') as $id => $attributes) {
            $container->getDefinition($id)->addMethodCall('addFormat', array('admin', array(
                'quality'       => 80,
                'width'         => 100,
                'format'        => 'jpg',
                'height'        => false,
                'constraint'    => true
            )));
        }
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return array
     */
    public function fixSettings(ContainerBuilder $container)
    {
        $pool = $container->getDefinition('zym_media.media_pool');

        // not very clean but don't know how to do that for now
        $settings = false;
        $methods  = $pool->getMethodCalls();
        foreach ($methods as $pos => $calls) {
            if ($calls[0] == '__hack__') {
                $settings = $calls[1];
                break;
            }
        }

        if ($settings) {
            unset($methods[$pos]);
        }

        $pool->setMethodCalls($methods);

        return $settings;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function attachProviders(ContainerBuilder $container)
    {
        $pool = $container->getDefinition('zym_media.media_pool');
        foreach ($container->findTaggedServiceIds('zym_media.provider') as $id => $attributes) {
            $pool->addMethodCall('addProvider', array($id, new Reference($id)));
        }
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $settings
     */
    public function attachArguments(ContainerBuilder $container, array $settings)
    {
        foreach ($container->findTaggedServiceIds('zym_media.provider') as $id => $attributes) {
            foreach($settings['providers'] as $name => $config) {
                if ($config['service'] == $id) {
                    $definition = $container->getDefinition($id);

                    $definition
                        ->replaceArgument(1, new Reference($config['filesystem']))
                        ->replaceArgument(2, new Reference($config['cdn']))
                        ->replaceArgument(3, new Reference($config['generator']))
                        ->replaceArgument(4, new Reference($config['thumbnail']))
                    ;

                    if ($config['resizer']) {
                        $definition->addMethodCall('setResizer', array(new Reference($config['resizer'])));
                    }
                }
            }
        }
    }

    /**
     * Define the default settings to the config array
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $settings
     */
    public function applyFormats(ContainerBuilder $container, array $settings)
    {
        foreach ($settings['contexts'] as $name => $context) {
            // add the differents related formats
            foreach ($context['providers'] as $id) {
                $definition = $container->getDefinition($id);

                foreach ($context['formats'] as $format => $config) {
                    $config['quality']      = isset($config['quality']) ? $config['quality'] : 80;
                    $config['format']       = isset($config['format'])  ? $config['format'] : 'jpg';
                    $config['height']       = isset($config['height'])  ? $config['height'] : false;
                    $config['constraint']   = isset($config['constraint'])  ? $config['constraint'] : true;

                    $formatName = sprintf('%s_%s', $name, $format);
                    $definition->addMethodCall('addFormat', array($formatName, $config));
                }
            }
        }
    }
}