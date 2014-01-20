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

namespace Zym\Bundle\RouterBundle\EventListener;

use Symfony\Component\Routing\RouterInterface;
use Zym\Bundle\RouterBundle\Entity\Route;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;

class RouteSubscriber implements EventSubscriber
{
    /**
     * Router
     *
     * @var Router;
     */
    private $router;
    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    
    public function getSubscribedEvents()
    {
        return array(
            Events::onFlush
        );
    }
    
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        if (!method_exists($this->router, 'getOption')) {
            return;
        }

        $em           = $eventArgs->getEntityManager();
        $uow          = $em->getUnitOfWork();
        $warmUpQueued = false;

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Route) {
                $warmUpQueued = true;
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Route) {
                $warmUpQueued = true;
            }
        }

//        foreach ($uow->getScheduledEntityDeletions() as $entity) {
//
//        }
//
//        foreach ($uow->getScheduledCollectionDeletions() as $col) {
//
//        }
//
//        foreach ($uow->getScheduledCollectionUpdates() as $col) {
//
//        }
        
        if ($warmUpQueued) {
            $cacheDir = $this->router->getOption('cache_dir');
            $generatorClass = $this->router->getOption('generator_cache_class');
            $matcherClass = $this->router->getOption('matcher_cache_class');

            $generatorFile = $cacheDir .'/' . $generatorClass . '.php';
            $matcherFile   = $cacheDir .'/' . $matcherClass . '.php';

            if (file_exists($generatorFile)) {
                unlink($cacheDir .'/' . $generatorClass . '.php');
            }

            if (file_exists($matcherFile)) {
                unlink($cacheDir .'/' . $matcherClass . '.php');
            }

            $this->router->getGenerator();
            $this->router->getMatcher();
        }        
    }
}