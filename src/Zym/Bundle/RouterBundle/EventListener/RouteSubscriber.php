<?php

namespace Zym\Bundle\RouterBundle\EventListener;

use Zym\Bundle\RouterBundle\Entity\Route;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Symfony\Component\Routing\Router;

class RouteSubscriber implements EventSubscriber
{
    /**
     * Router
     *
     * @var Router;
     */
    private $router;
    
    public function __construct(Router $router)
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
            
            if (file_exists($cacheDir .'/' . $generatorClass . '.php')) {
                unlink($cacheDir .'/' . $generatorClass . '.php');
            }
            
            if (file_exists($cacheDir .'/' . $matcherClass . '.php')) {
                unlink($cacheDir .'/' . $matcherClass . '.php');
            }
            
            $this->router->getGenerator();
            $this->router->getMatcher();
        }        
    }
}