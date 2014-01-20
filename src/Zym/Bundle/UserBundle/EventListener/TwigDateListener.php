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

namespace Zym\Bundle\UserBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zym\Bundle\UserBundle\Model\TimeZoneInterface;

/**
 * Class TwigDateListener
 *
 * @package Zym\Bundle\UserBundle\EventListener
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class TwigDateListener
{
    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironment;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * Construct
     *
     * @param \Twig_Environment        $twigEnvironment
     * @param SecurityContextInterface $securityContext
     */
    function __construct(\Twig_Environment $twigEnvironment, SecurityContextInterface $securityContext = null)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->securityContext = $securityContext;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $securityContext = $this->securityContext;

        if ($securityContext
            && $securityContext->getToken() instanceof TokenInterface
            && $securityContext->getToken()->getUser() instanceof TimeZoneInterface) {

            $timeZone = $securityContext->getToken()->getUser()->getTimezone();

            if ($timeZone) {
                $this->twigEnvironment->getExtension('core')->setTimezone($timeZone);
            }
        }
    }
}
