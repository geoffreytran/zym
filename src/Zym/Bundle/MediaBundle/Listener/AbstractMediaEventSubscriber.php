<?php

namespace Zym\Bundle\MediaBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;

use Zym\Bundle\MediaBundle\Model\MediaInterface;
use Zym\Bundle\MediaBundle\MediaPool;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractMediaEventSubscriber implements EventSubscriber
{
    private $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return MediaPool
     */
    public function getPool()
    {
        return $this->container->get('zym_media.media_pool');
    }

    /**
     * @abstract
     *
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    abstract protected function recomputeSingleEntityChangeSet(EventArgs $args);

     /**
     * @abstract
     *
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return \Sonata\MediaBundle\Model\MediaInterface
     */
    abstract protected function getMedia(EventArgs $args);

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return \Sonata\MediaBundle\Provider\MediaProviderInterface
     */
    protected function getProvider(EventArgs $args)
    {
        $media = $this->getMedia($args);

        if (!$media instanceof MediaInterface) {
            return null;
        }

        return $this->getPool()->getProvider($media->getProviderName());
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function postUpdate(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->postUpdate($this->getMedia($args));
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function postRemove(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->postRemove($this->getMedia($args));
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function postPersist(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->postPersist($this->getMedia($args));
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function preUpdate(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->transform($this->getMedia($args));
        $provider->preUpdate($this->getMedia($args));

        $this->recomputeSingleEntityChangeSet($args);
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function preRemove(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->preRemove($this->getMedia($args));
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     *
     * @return void
     */
    public function prePersist(EventArgs $args)
    {
        if (!($provider = $this->getProvider($args))) {
            return;
        }

        $provider->transform($this->getMedia($args));
        $provider->prePersist($this->getMedia($args));
    }
}