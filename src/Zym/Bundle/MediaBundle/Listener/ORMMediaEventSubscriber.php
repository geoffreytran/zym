<?php

namespace Zym\Bundle\MediaBundle\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Sonata\MediaBundle\Listener\BaseMediaEventSubscriber;

class ORMMediaEventSubscriber extends AbstractMediaEventSubscriber
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postUpdate,
            Events::postRemove,
            Events::postPersist,
        );
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return void
     */
    protected function recomputeSingleEntityChangeSet(EventArgs $args)
    {
        $em = $args->getEntityManager();

        $em->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $em->getClassMetadata(get_class($args->getEntity())),
            $args->getEntity()
        );
    }

    /**
     * @inheritdoc
     */
    protected function getMedia(EventArgs $args)
    {
        return $args->getEntity();
    }
}