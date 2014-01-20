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

namespace Zym\Bundle\FieldBundle;

use Zym\Bundle\FieldBundle\FieldableInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

class FieldableEntityListener implements EventSubscriber
{
    
    /**
     * Get subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
            Events::postPersist,
            Events::postUpdate
        );
    }
    
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em     = $eventArgs->getEntityManager();
        if (!$entity instanceof FieldableInterface) {
            return;
        }
        
        $collection = new FieldCollection($entity, $em);
        $entity->setFields($collection);
    }
    
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em     = $eventArgs->getEntityManager();

        if (!$entity instanceof FieldableInterface) {
            return;
        }

        foreach ($entity->getFields() as $field) {
            if (is_array($field) || $field instanceof \ArrayObject) {
                foreach ($field as $f) {
                    $em->persist($f);
                }
            } else {
                $em->persist($field);
            }
        }
        
        $em->flush();
    }
    
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em     = $eventArgs->getEntityManager();

        if (!$entity instanceof FieldableInterface) {
            return;
        }

        foreach ($entity->getFields() as $field) {
            $em->persist($field);
        }
        
        $em->flush();
    }
}