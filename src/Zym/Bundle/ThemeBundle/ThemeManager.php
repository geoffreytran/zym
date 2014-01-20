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

namespace Zym\Bundle\ThemeBundle;

use Zym\Bundle\ThemeBundle\Resolver\ResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ThemeManager
 *
 * @package Zym\Bundle\ThemeBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ThemeManager
{
    private $resolvers = array();
    private $themes    = array();

    /**
     * Active Theme
     *
     * @var string
     */
    private $activeTheme;

    public function __construct(array $resolvers = array())
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

    public function addResolver(ResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
        return $this;
    }

    public function resolveTheme(Request $request)
    {
        foreach ($this->resolvers as $resolver) {
            /** @var $resolver ResolverInterface */

            try {
                $theme = $resolver->resolve($request);
                $this->setActiveTheme($theme);
                break;
            } catch (Resolver\NoMatchException $e) {
                continue;
            }
        }
    }

    public function setThemes(array $themes)
    {
        $this->themes = $themes;
        return $this;
    }

    public function getThemes()
    {
        return $this->themes;
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