<?php

namespace Zym\Bundle\UserBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Zym\Bundle\UserBundle\Model\TimeZoneInterface;

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

    function __construct(\Twig_Environment $twigEnvironment, SecurityContextInterface $securityContext = null)
    {
        $this->twigEnvironment = $twigEnvironment;
        $this->securityContext = $securityContext;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $securityContext = $this->securityContext;

        if ($securityContext
            && $securityContext->getToken() instanceof TokenInterface
            && $securityContext->getToken()->getUser() instanceof TimeZoneInterface) {
            $this->twigEnvironment->getExtension('core')->setTimezone($securityContext->getToken()->getUser()->getTimezone());
        }
    }
}
