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

namespace Zym\Bundle\SecurityBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Access Rule Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class AccessRuleRepository extends AbstractEntityRepository
{
    /**
     * Find AccessRules
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findAccessRules(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('r');
        $this->setQueryBuilderOptions($qb, $criteria, $orderBy);

        $query     = $qb->getQuery();
        $paginator = $this->getPaginator();

        return $paginator->paginate($query, $page, $limit);
    }
}