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

namespace Zym\Security\Http;

use Symfony\Component\Security\Http\AccessMap as BaseAccessMap;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccessMap
 *
 * @package Zym\Security\Http
 * @author Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class AccessMap extends BaseAccessMap 
{
    /**
     * @var array
     */
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

    /**
     * Get patterns
     *
     * @param Request $request
     *
     * @return array
     */
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