<?php

namespace Zym\Bundle\ThemeBundle;

use Symfony\Component\HttpFoundation\Request;

class ThemeManager
{
    private $resolvers = array();
    
    /**
     * Active Theme
     *
     * @var string
     */
    private $activeTheme;
    
    public function __construct(array $resolvers)
    {
        $this->resolvers = $resolvers;
    }
    
    public function getResolvers()
    {
        return $this->resolvers;
    }
    
    public function setResolvers(array $resolvers)
    {
        $this->resolvers = $resolvers;
        return $this;
    }
    
    public function addResolver(Resolver $resolver)
    {
        $this->resolvers[] = $resolvers;
        return $this;
    }
    
    public function resolveTheme(Request $request)
    {
        foreach ($this->resolvers as $resolver) {
            /** @var $resolver Resolver\ResolverInterface */
            
            try {
                $theme = $resolver->resolve($request);
                $this->setActiveTheme($theme);
                break;
            } catch (Resolver\NoMatchException $e) {
                continue;
            }
        }   
    }
    
    public function getActiveTheme()
    {   
        return $this->activeTheme;
    }
    
    public function setActiveTheme($theme)
    {
        $this->activeTheme = $theme;
        return $this;
    }
    

}