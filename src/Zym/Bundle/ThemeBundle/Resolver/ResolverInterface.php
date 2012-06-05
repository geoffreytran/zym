<?php

namespace Zym\Bundle\ThemeBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;

interface ResolverInterface
{
    /**
    * Resolve the active theme
    *
    * @param Request $request
    * @return string
    */
    public function resolve(Request $request);
}