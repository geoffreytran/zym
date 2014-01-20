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

namespace Zym\Bundle\MenuBundle\Entity\MenuItem;

use Zym\Bundle\MenuBundle\Entity\MenuItem\SectionMenuItem;
use Zym\Bundle\RouterBundle\Entity\Route;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SectionMenuItemListener
{
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $om    = $eventArgs->getEntityManager();
        $entity = $eventArgs->getEntity();

        if ($entity instanceof SectionMenuItem) {
            $routeName = 'zym_menu_section_' . $entity->getId();

            $route = new Route($routeName, $entity->getUri(), array(
                'id'          => $entity->getId(),
                '_controller' => 'ZymMenuBundle:Categories:list'
            ));

            $entity->setRoute($routeName);

            $om->persist($route);
            $om->flush($route);
        }
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $om    = $eventArgs->getEntityManager();
        $entity = $eventArgs->getEntity();

        if ($entity instanceof SectionMenuItem) {
            $route = $om->find('ZymRouterBundle:Route', 'zym_menu_section_' . $entity->getId());
            if ($route) {
                $route->setPath($entity->getUri());
                $route->setDefaults(array(
                    'id'          => $entity->getId(),
                    '_controller' => 'ZymMenuBundle:Categories:list'
                ));

                $om->persist($route);
                //$om->flush($route);
            }

        }
    }

    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $om    = $eventArgs->getEntityManager();
        $entity = $eventArgs->getEntity();

        if ($entity instanceof SectionMenuItem) {
            $route = $om->find('ZymRouterBundle:Route', 'zym_menu_section_' . $entity->getId());
            if ($route) {
                $om->remove($route);
                $om->flush($route);
            }
        }
    }
}