<?php
/**
 * RAPP
 *
 * LICENSE
 *
 * This file is intellectual property of RAPP and may not
 * be used without permission.
 *
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */

namespace Zym\Bundle\MenuBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class MenuRepository extends AbstractEntityRepository
{
    /**
     * Find all menus by criteria
     *
     * @param array $criteria
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findMenus(array $criteria = null, $page = 1, $limit = 10, array $orderBy = null)
    {
        return $this->findEntities($criteria, $page, $limit, $orderBy);
    }

    /**
     * Whether a menu exists
     *
     * @param array $criteria
     * @return boolean
     */
    public function hasMenuBy(array $criteria)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('COUNT(m.name)');

        $this->setQueryOptions($qb, $criteria);

        $query = $qb->getQuery();

        return (bool) $query->getSingleScalarResult();
    }
}