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

use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface AccessRuleProviderInterface 
{
    /**
     * Get the access rules
     *
     * @return array Array of Zym\Bundle\SecurityBundle\Http\AccessRuleInterface
     */
    public function getRules();
}