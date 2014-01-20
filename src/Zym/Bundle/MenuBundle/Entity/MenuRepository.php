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

namespace Zym\Bundle\MenuBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
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

        $this->setQueryBuilderOptions($qb, $criteria);

        $query = $qb->getQuery();

        return (bool) $query->getSingleScalarResult();
    }
}