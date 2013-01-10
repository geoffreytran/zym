<?php
namespace Zym\Bundle\MediaBundle\Entity;

use Zym\Bundle\MediaBundle\Model\MediaManagerInterface;
use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Media Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class MediaManager extends AbstractEntityManager implements MediaManagerInterface
{
    /**
     * Repository
     *
     * @var MediaRepository
     */
    protected $repository;

    /*
     * Create an media
     *
     * @param Media $media
     * @return Media
     */
    public function createMedia(Media $media)
    {
        parent::createEntity($media);
        return $media;
    }

    /**
     * Delete an media
     *
     * @param Media $media
     */
    public function deleteMedia(Media $media)
    {
        parent::deleteEntity($media);
    }

    /**
     * Save an media
     *
     * @param Media $media
     * @param boolean $andFlush
     */
    public function saveMedia(Media $media, $andFlush = true)
    {
        $this->saveEntity($media, $andFlush);
    }

    /**
     * Find an media
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findMedias(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findMedias($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find an media by criteria
     *
     * @param array $criteria
     * @return Media
     */
    public function findMediaBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}