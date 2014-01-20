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

namespace Zym\Bundle\RouterBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Route Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RouteManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var RouteRepository
     */
    protected $repository;

    /*
     * Create a route
     *
     * @param Route $route
     * @return Route
     */
    public function createRoute(Route $route)
    {
        parent::createEntity($route);

        return $route;
    }

    /**
     * Delete a route
     *
     * @param Route $route
     */
    public function deleteRoute(Route $route)
    {
        parent::deleteEntity($route);
    }

    /**
     * Save a route
     *
     * @param Route $route
     * @param boolean $andFlush
     */
    public function saveRoute(Route $route, $andFlush = true)
    {
        $this->saveEntity($route, $andFlush);
    }

    /**
     * Find a route by criteria
     *
     * @param array $criteria
     * @return Route
     */
    public function findRouteBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Whether a route exists
     *
     * @param array $criteria
     * @return boolean
     */
    public function hasRouteBy(array $criteria)
    {
        return $this->repository->hasRouteBy($criteria);
    }

    /**
     * Find all routes by criteria
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findRoutes(array $criteria = null, $page = 1, $limit = 10,array $orderBy = null)
    {
        $entities = $this->repository->findRoutes($criteria, $page, $limit, $orderBy);
        $this->loadAcls($entities);
        return $entities;
    }
}