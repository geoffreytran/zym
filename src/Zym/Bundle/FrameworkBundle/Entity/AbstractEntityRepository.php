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

namespace Zym\Bundle\FrameworkBundle\Entity;

use Zym\Bundle\FrameworkBundle\Model\Criteria;
use Zym\Bundle\FrameworkBundle\Model\PageableRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Abstract Entity Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
abstract class AbstractEntityRepository extends EntityRepository
                                        implements PageableRepositoryInterface
{
    /**
     * Paginator
     *
     * @var Paginator
     */
    private $paginator;

    /**
     * Find
     *
     * @param array $criteria
     * @param array $orderBy
     * @return PaginationInterface
     */
    protected function findEntities(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('n');
        $this->setQueryBuilderOptions($qb, $criteria, $orderBy);

        $query     = $qb->getQuery();
        $paginator = $this->getPaginator();

        return $paginator->paginate($query, $page, $limit);
    }

    /**
     * Get the paginator
     *
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * Set the paginator
     *
     * @param Paginator $paginator
     * @return AbstractEntityRepository
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * Process querybuilder
     *
     * @param \Doctrine\DBAL\Query\QueryBuilder $qb
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @param array $options
     * @return PaginationInterface
     */
    protected function processQueryBuilder(QueryBuilder $qb, array $criteria = null, $page = 1, $limit = 50, array $orderBy = null, array $options = null)
    {
        $this->setQueryBuilderOptions($qb, $criteria, $orderBy, $options);

        $query = $qb->getQuery();
        $this->setQueryOptions($query, $options);

        $paginator = $this->getPaginator();

        return $paginator->paginate($query, $page, $limit);
    }

    /**
     * Set the query options
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param array $criteria
     * @param array $orderBy
     * @param array $options
     */
    protected function setQueryBuilderOptions(QueryBuilder $qb, array $criteria = null, array $orderBy = null, array $options = null)
    {
        if ($criteria) {
            foreach ($criteria as $key => $value) {
                $paramName = 'qo_' . str_replace('.', '_', $key);
                $x         = $qb->getRootAlias() . '.' . $key;

                if (is_array($value) && isset($value['value'])) {
                    if (!isset($value['operator'])) {
                        $value['operator'] = '=';
                    }

                    switch (strtolower($value['operator'])) {
                        case '<>':
                            $expr = new Expr\Comparison($x, '<>', ':' . $paramName);
                            break;
                        
                        case '<':
                            $expr = new Expr\Comparison($x, '<', ':' . $paramName);
                            break;
                        
                        case '<=':
                            $expr = new Expr\Comparison($x, '<=', ':' . $paramName);
                            break;
                        
                        case '>':
                            $expr = new Expr\Comparison($x, '>', ':' . $paramName);
                            break;
                        
                        case '>=':
                            $expr = new Expr\Comparison($x, '>=', ':' . $paramName);
                            break;
                        
                        case 'contains':
                            $expr = new Expr\Comparison($x, 'LIKE', ':' . $paramName);
                            $value['value'] = '%' . $value['value'] . '%';
                            break;
                        
                        case 'not-contains':
                            $expr = new Expr\Comparison($x, 'NOT LIKE', ':' . $paramName);
                            $value['value'] = '%' . $value['value'] . '%';
                            break;
                        
                        case 'between':
                            $expr = $x . ' BETWEEN ' . ':' . $paramName . ' AND :' . $paramName . '_2';
                            $qb->setParameter($paramName . '_2', isset($value['value2']) ? $value['value2'] : null);
                            break;
                        
                        case '=':
                        default:
                            $expr = new Expr\Comparison($x, '=', ':' . $paramName);
                            break;
                    }
                    
                    $qb->setParameter($paramName, $value['value']);
                } else {
                    if ($value instanceof Criteria\Comparison) {
                        $expr = new Expr\Comparison($x, $value->getOperator(), ':' . $paramName);
                        $qb->setParameter($paramName, $value->getRightExpr());
                    } else if ($value === null) {
                        $expr = $qb->expr()->isNull($x);
                    } else {
                        $expr = $qb->expr()->eq($x, ':' . $paramName);
                        $qb->setParameter($paramName, $value);
                    }
                }

                $qb->andWhere($expr);
            }
        }

        if ($orderBy) {
            foreach ($orderBy as $column => $direction) {
                $qb->addOrderBy(sprintf('%s.%s', $qb->getRootAlias(), $column), $direction);
            }
        }

        if ($options) {
            if (isset($options['result_cache'])) {
                if (is_array($options['result_cache'])) {

                }
            }
        }
    }

    /**
     * Set the query options
     *
     * @param \Doctrine\ORM\Query $query
     * @param array $options
     */
    protected function setQueryOptions(Query $query, array $options = null)
    {
        if (!$options) {
            return;
        }

        if (isset($options['result_cache'])) {
            if (is_array($options['result_cache'])) {
                foreach ($options['result_cache'] as $key => $value) {
                    switch ($key) {
                        case 'enabled':
                            $query->useResultCache($value);
                            break;

                        case 'lifetime':
                            $query->setResultCacheLifetime($value);
                            break;

                        case 'id':
                            $query->setResultCacheId($value);
                            break;
                    }
                }
            }
        }
    }
}