<?php

namespace Zym\Security\Http;

use Symfony\Component\Security\Http\AccessMap as BaseAccessMap;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class AccessMap extends BaseAccessMap 
{
    private $map = array();

    /**
     * Add a rule
     *
     * @param RequestMatcherInterface $requestMatcher A RequestMatcherInterface instance
     * @param array                   $roles          An array of roles needed to access the resource
     * @param string|null             $channel        The channel to enforce (http, https, or null)
     */
    public function add(RequestMatcherInterface $requestMatcher, array $roles = array(), $channel = null)
    {
        $this->map[] = array($requestMatcher, $roles, $channel);
    }
    
    /**
     * Prepend a rule.
     *
     * @param RequestMatcherInterface $requestMatcher A RequestMatcherInterface instance
     * @param array                   $roles          An array of roles needed to access the resource
     * @param string|null             $channel        The channel to enforce (http, https, or null)
     */
    public function prepend(RequestMatcherInterface $requestMatcher, array $roles = array(), $channel = null)
    {
        array_unshift($this->map, array($requestMatcher, $roles, $channel));
    }
    
    public function getPatterns(Request $request)
    {
        foreach ($this->map as $elements) {
            if (null === $elements[0] || $elements[0]->matches($request)) {
                return array($elements[1], $elements[2]);
            }
        }

        return array(null, null);
    }
}