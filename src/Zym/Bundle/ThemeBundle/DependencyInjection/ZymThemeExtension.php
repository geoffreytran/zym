<?php

namespace Zym\Bundle\ThemeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZymThemeExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter($this->getAlias().'.themes', array());

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $this->createThemeMap($config, $container);
    }
    
    private function createThemeMap($config, ContainerBuilder $container)
    {
        if (!$config['theme_map']) {
            return;
        }
    
        $this->addClassesToCompile(array(
            'Zym\\Bundle\\ThemeBundle\\Resolver\\ResolverInterface',
            //'Zym\\Bundle\\ThemeBundle\\Resolver\\RequestMapResolver',
        ));
    
        foreach ($config['theme_map'] as $rule) {
            $matcher = $this->createRequestMatcher(
                $container,
                $rule['path'],
                $rule['host'],
                count($rule['methods']) === 0 ? null : $rule['methods'],
                $rule['ip']
            );
    
            $container->getDefinition('zym_theme.resolver.request_map')
                      ->addMethodCall('add', array($matcher, $rule['theme']));
                      
            $container->setParameter($this->getAlias().'.themes', 
                array_merge(array(
                        $rule['theme']
                    ), 
                    $container->getParameter($this->getAlias().'.themes')
                )
            );

        }
    }
    
    private function createRequestMatcher($container, $path = null, $host = null, $methods = null, $ip = null, array $attributes = array())
    {
        $serialized = serialize(array($path, $host, $methods, $ip, $attributes));
        $id = 'zym_theme.request_matcher.'.md5($serialized).sha1($serialized);
    
        if (isset($this->requestMatchers[$id])) {
            return $this->requestMatchers[$id];
        }
    
        // only add arguments that are necessary
        $arguments = array($path, $host, $methods, $ip, $attributes);
        while (count($arguments) > 0 && !end($arguments)) {
            array_pop($arguments);
        }
    
        $container
            ->register($id, '%security.matcher.class%')
            ->setPublic(false)
            ->setArguments($arguments)
        ;
    
        return $this->requestMatchers[$id] = new Reference($id);
    }
}
