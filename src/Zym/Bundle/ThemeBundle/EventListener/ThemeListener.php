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

namespace Zym\Bundle\ThemeBundle\EventListener;

use Zym\Bundle\ThemeBundle\ThemeManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class ThemeListener
 *
 * @package Zym\Bundle\ThemeBundle\EventListener
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ThemeListener
{
    /**
     * Theme Manager
     *
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * @param ActiveTheme              $activeTheme
     * @param array                    $cookieOptions The options of the cookie we look for the theme to set
     * @param DeviceDetectionInterface $autoDetect    If to auto detect the theme based on the user agent
     */
    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $this->themeManager->resolveTheme($event->getRequest());     
        }
    }
}
