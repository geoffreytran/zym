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

namespace Zym\Bundle\FieldBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * FieldConfig Repository
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class FieldConfigRepository extends AbstractEntityRepository
{
    /**
     * Find Fields Configs
     *
     * @param array $criteria
     * @param array $orderBy
     * @return Paginator
     */
    public function findFieldConfigs(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('fc');
        $this->setQueryBuilderOptions($qb, $criteria, $orderBy);

        $query     = $qb->getQuery();
        $paginator = $this->getPaginator();

        return $paginator->paginate($query, $page, $limit);
    }
}