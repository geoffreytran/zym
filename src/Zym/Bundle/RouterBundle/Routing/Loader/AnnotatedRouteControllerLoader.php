<?php

namespace Zym\Bundle\RouterBundle\Routing\Loader;

use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader as BaseAnnotatedRouteControllerLoader;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\AnnotationReader as ConfigurationAnnotationReader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class AnnotatedRouteControllerLoader extends BaseAnnotatedRouteControllerLoader
{
    /**
     * Configures the _controller default parameter and eventually the _method
     * requirement of a given Route instance.
     *
     * @param Route $route A Route instance
     * @param ReflectionClass $class A ReflectionClass instance
     * @param ReflectionMethod $method A ReflectionClass method
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        parent::configureRoute($route, $class, $method, $annot);
        
        $pattern = $route->getPattern();
        if (!empty($pattern) && '/' === $pattern[0] && isset($pattern[1]) && '.' === $pattern[1]) {
            $r = new \ReflectionProperty('Symfony\Component\Routing\Route', 'pattern');
            $r->setAccessible(true);
            $r->setValue($route, substr($pattern, 1));
        }
    }
}
