<?php

namespace Zym\Bundle\ThemeBundle\Resolver;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestMapResolver implements ResolverInterface
{
    /**
     * Theme Map
     *
     * @var array
     */
    private $map = array();
    
    /**
     * Add a theme to be matched to a request
     *
     * @param RequestMatcherInterface $requestMatcher
     * @param string $theme
     * @return RequestMapResolver
     */
    public function add(RequestMatcherInterface $requestMatcher, $theme)
    {
        $this->map[$theme] = $requestMatcher;
        return $this;
    }
    
    /**
     * Resolve the active theme
     *
     * @param Request $request
     * @return string
     */
    public function resolve(Request $request)
    {
        foreach ($this->map as $theme => $requestMatcher) {
            if ($requestMatcher->matches($request)) {
                return $theme;
            }
        }    
        
        throw new NoMatchException();
    }
}