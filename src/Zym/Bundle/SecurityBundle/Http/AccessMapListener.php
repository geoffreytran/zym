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

use Zym\Security\Http\AccessMap;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AccessMapListener
{
    /**
     * Access Map
     *
     * @var AccessMap
     */
    protected $accessMap;

    /**
     * Access Rule Provider
     *
     * @var AccessRuleProviderInterface
     */
    protected $accessRuleProvider;

    /**
     * Construct
     *
     * @param AccessMap $accessMap
     * @param AccessRuleProvider $accessRuleProvider
     */
    public function __construct(AccessMap $accessMap, AccessRuleProviderInterface $accessRuleProvider)
    {
        $this->accessMap          = $accessMap;
        $this->accessRuleProvider = $accessRuleProvider;
    }

    /**
     * onKernelRequest
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            // return immediately
            return;
        }

        $accessMap          = $this->accessMap;
        $accessRuleProvider = $this->accessRuleProvider;
        foreach ($accessRuleProvider->getRules() as $rule) {
            $accessMap->prepend($rule->getRequestMatcher(), $rule->getRoles(), $rule->getChannel());
        }
    }
}