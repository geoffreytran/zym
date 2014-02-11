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

namespace Zym\Bundle\FrameworkBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class RestFormCsrfListener
 *
 * @package Zym\Bundle\FrameworkBundle\EventListener
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RestFormCsrfSubscriber implements EventSubscriberInterface
{
    /**
     * @var CsrfTokenManagerInterface
     */
    protected $csrfTokenManager;

    /**
     * @var string
     */
    protected $headerName;

    /**
     * @var string
     */
    protected $cookieName;

    /**
     * @var int
     */
    protected $cookieExpire;

    /**
     * @var string
     */
    protected $cookiePath;

    /**
     * @var string
     */
    protected $cookieDomain;

    /**
     * @var bool
     */
    protected $cookieSecure;

    /**
     * Construct
     *
     * @param CsrfTokenManagerInterface $csrfTokenManagerInterface
     * @param string $cookieName
     * @param integer $cookieExpire
     * @param string $cookiePath
     * @param string $cookieDomain
     * @param boolean $cookieSecure
     */
    function __construct(
        CsrfTokenManagerInterface $csrfTokenManager,
        $headerName   = 'X-XSRF-TOKEN',
        $cookieName   = 'XSRF-TOKEN',
        $cookieExpire = 0,
        $cookiePath   = '/',
        $cookieDomain = null,
        $cookieSecure = false)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->headerName       = $headerName;
        $this->cookieName       = $cookieName;
        $this->cookieExpire     = $cookieExpire;
        $this->cookiePath       = $cookiePath;
        $this->cookieDomain     = $cookieDomain;
        $this->cookieSecure     = $cookieSecure;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request'  => 'onKernelRequest',
            'kernel.response' => 'onKernelResponse'
        );
    }

    /**
     * Handles CSRF token validation
     *
     * @param  GetResponseEvent          $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->headers->has($this->headerName)) {
            $value        = $request->headers->get($this->headerName);

            $tokenManager = $this->csrfTokenManager;
            $token        = new CsrfToken('rest_csrf', $value);

            $request->attributes->set('_rest_csrf_valid', $tokenManager->isTokenValid($token));
        }
    }

    /**
     * onKernelResponse
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $event->getResponse()->headers->setCookie(new Cookie(
            $this->cookieName,
            $this->csrfTokenManager->getToken('rest_csrf')->getValue(),
            $this->cookieExpire,
            $this->cookiePath,
            $this->cookieDomain,
            $this->cookieSecure,
            false
        ));
    }
}