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
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */

namespace Zym\Bundle\FrameworkBundle\Entity;

use Zym\Bundle\FrameworkBundle\Modal\Criteria;
use Zym\Bundle\FrameworkBundle\Model\PageableRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
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
        $this->setQueryOptions($qb, $criteria, $orderBy);

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
     * Set the query options
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param array $criteria
     * @param array $orderBy
     */
    protected function setQueryOptions(\Doctrine\ORM\QueryBuilder $qb, array $criteria = null, array $orderBy = null)
    {
        if ($criteria) {
            foreach ($criteria as $key => $value) {
                $paramName = 'qo_' . $key;
                $x         = $qb->getRootAlias() . '.' . $key;

                if ($value instanceof Criteria\Comparison) {
                    $expr = new Expr\Comparison($x, $value->getOperator(), $paramName);
                } else {
                    $expr = $qb->expr()->eq($x, ':' . $paramName);
                }

                $qb->andWhere($expr);
                $qb->setParameter($paramName, $value);
            }
        }

        if ($orderBy) {
            foreach ($orderBy as $column => $direction) {
                $qb->addOrderBy(sprintf('%s.%s', $qb->getRootAlias(), $column), $direction);
            }
        }
    }
}