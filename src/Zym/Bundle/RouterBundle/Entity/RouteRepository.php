<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 */

namespace Zym\Bundle\RouterBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Bundle\PaginatorBundle\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator\Paginator;

/**
 * Route Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
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