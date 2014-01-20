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

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Bundle\PaginatorBundle\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator\Paginator;

/**
 * Route Repository
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class RouteRepository extends AbstractEntityRepository
{
    /**
     * Find routes
     *
     * @param array $critera
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findRoutes(array $criteria = null, $page = 1, $limit = 10, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('r');
        $this->setQueryBuilderOptions($qb, $criteria, $orderBy);

        $query     = $qb->getQuery();
        $paginator = $this->getPaginator();

        return $paginator->paginate($query, $page, $limit);
    }

    public function findLatestUpdatedTimestamp()
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select('r.updatedAt')
           ->orderBy('r.updatedAt', 'DESC')
           ->setMaxResults(1);

        $query = $qb->getQuery();
        $time  =  $query->getSingleScalarResult();

    }
}