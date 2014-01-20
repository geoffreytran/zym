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

namespace Zym\Bundle\SecurityBundle\Http;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;

interface AccessRuleInterface 
{
    /**
     * Get the request matcher
     *
     * @return RequestMatcher
     */
    public function getRequestMatcher();
    
    /**
     * Get the roles
     *
     * @return array
     */
    public function getRoles();
    
    /**
     * Get the required channel
     *
     * @return string
     */
    public function getChannel();
}