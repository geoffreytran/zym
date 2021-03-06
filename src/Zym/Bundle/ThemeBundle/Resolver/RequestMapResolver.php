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

namespace Zym\Bundle\ThemeBundle\Resolver;

use Zym\Bundle\ThemeBundle\Entity\ThemeRuleManager;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestMapResolver
 *
 * @package Zym\Bundle\ThemeBundle\Resolver
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RequestMapResolver implements ResolverInterface
{
    /**
     * Theme Map
     *
     * @var array
     */
    private $map = array();

    /**
     * ThemeRuleManager
     *
     * @var ThemeRuleManager
     */
    private $themeRuleManager;

    private $initiated = false;

    /**
     * Construct
     *
     */
    public function __construct(ThemeRuleManager $themeRuleManager)
    {
        $this->themeRuleManager = $themeRuleManager;
    }

    /**
     * Sleep
     *
     */
    public function __sleep()
    {
        return array('map', 'initiated');
    }

    /**
     * Add a theme to be matched to a request
     *
     * @param RequestMatcherInterface $requestMatcher
     * @param string $theme
     * @return RequestMapResolver
     */
    public function add(RequestMatcherInterface $requestMatcher, $theme)
    {
        $this->map[] = array($requestMatcher, $theme);
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
        if (!$this->initiated) {
            $rules = $this->themeRuleManager->getRules();
            foreach ($rules as $rule) {
                $this->add($rule->getRequestMatcher(), $rule->getTheme());
            }

            $this->initiated = true;
        }

        foreach (array_reverse($this->map) as $rule) {
            $requestMatcher = $rule[0];
            $theme          = $rule[1];
            
            if ($requestMatcher->matches($request)) {
                return $theme;
            }
        }

        throw new NoMatchException();
    }
}