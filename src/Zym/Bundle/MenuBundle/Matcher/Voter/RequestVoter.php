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

namespace Zym\Bundle\MenuBundle\Matcher\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Voter based on the uri
 */
class RequestVoter implements VoterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container->get('request');

        if ($item->getUri() === $request->getRequestUri()) {
            return true;
        }

//        if ($item instanceof \Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem) {
//            /* @var $router \Symfony\Component\Routing\Router */
//            $router = $this->container->get('router');
//            $route  = $router->match($request->getPathInfo());
//
//            if ($item->getRoute() == $route['_route'] && ) {
//
//            }
//        }

        return null;
    }
}