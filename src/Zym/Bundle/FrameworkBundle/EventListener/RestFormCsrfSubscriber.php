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
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class RestFormCsrfSubscriber
 *
 * @package Zym\Bundle\FrameworkBundle\EventListener
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RestFormCsrfSubscriber implements  EventSubscriberInterface
{
    /**
     * @var CsrfToken
     */
    protected $csrfTokenManager;

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
            'kernel.request' => 'onKernelRequest',
            'kernel.response'
        );
    }

    /**
     * onKernelRequest
     *
     * @param GetResponseEvent $e
     */
    public function onKernelRequest(GetResponseEvent $e)
    {

    }

}