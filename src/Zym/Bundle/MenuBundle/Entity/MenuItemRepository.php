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
use Zym\Bundle\FrameworkBundle\Model\PageableRepositoryInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Item Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class MenuItemRepository extends NestedTreeRepository
                         implements PageableRepositoryInterface
{
    /**
     * Paginator
     *
     * @var Paginator
     */
    private $paginator;

    /**
     * Find root menu items by menu
     *
     * @param Menu $menu
     * @param array $criteria
     * @param array $orderBy
     * @return array
     */
    public function findRootMenuItemsByMenu(Menu $menu, array $criteria = null, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('mi');
        $qb->where('mi.menu = :menu')
           ->andWhere('mi.parent IS NULL')
           ->leftJoin('mi.children', 'c')
           ->orderBy('mi.weight', 'ASC')
           ->addOrderBy('mi.lft', 'ASC')
           ->addOrderBy('mi.id', 'ASC');

        $qb->setParameter('menu', $menu->getName());

        $this->setQueryOptions($qb, $criteria, $orderBy);

        $query = $qb->getQuery();

        return $query->getResult();
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
     * @return MenuItemRepository
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
                $qb->andWhere(sprintf('%s.%s = :%s', $qb->getRootAlias(), $key, $paramName));
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