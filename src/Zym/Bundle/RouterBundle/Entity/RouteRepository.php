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