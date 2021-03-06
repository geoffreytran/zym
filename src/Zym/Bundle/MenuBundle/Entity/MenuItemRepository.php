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
use Zym\Bundle\FrameworkBundle\Model\PageableRepositoryInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Item Repository
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
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
     * Finds menu items by a set of criteria regardless of type.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array The objects.
     */
    public function findAllMenuItemsBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $repository = $this->getEntityManager()->getRepository('ZymMenuBundle:MenuItem');
        return $repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Find menu item by name
     *
     * @param \Zym\Bundle\MenuBundle\Entity\Menu $menu
     * @param string $name
     * @return MenuItem
     */
    public function findMenuItemByName(Menu $menu, $name)
    {
        $qb = $this->createQueryBuilder('mi');
        $qb->select('mi, c')
           ->where('mi.menu = :menu')
           ->andWhere('mi.name = :name')
           ->leftJoin('mi.children', 'c');

        $qb->setParameter('menu', $menu->getName());
        $qb->setParameter('name', $name);

        $query    = $qb->getQuery();
        $menuItem = $query->getOneOrNullResult();

        return $menuItem;
    }

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
        $qb->select('mi, c, ci')
           ->where('mi.menu = :menu')
           ->andWhere('mi.parent IS NULL')
           ->leftJoin('mi.children', 'c')
           ->leftJoin('c.children', 'ci')
           ->orderBy('mi.weight', 'ASC')
           ->addOrderBy('mi.lft', 'ASC')
           ->addOrderBy('mi.id', 'ASC');

        $qb->setParameter('menu', $menu->getName());

        $this->setQueryBuilderOptions($qb, $criteria, $orderBy);

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
    protected function setQueryBuilderOptions(\Doctrine\ORM\QueryBuilder $qb, array $criteria = null, array $orderBy = null)
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