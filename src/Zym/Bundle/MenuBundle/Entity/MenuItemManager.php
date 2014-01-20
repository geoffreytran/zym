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

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;

use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Item Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class MenuItemManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var MenuItemRepository
     */
    protected $repository;

    /*
     * Create a menu item
     *
     * @param MenuItem $menuItem
     * @return MenuItem
     */
    public function createMenuItem(MenuItem $menuItem)
    {
        parent::createEntity($menuItem);

        return $menuItem;
    }

    /**
     * Delete a menu item
     *
     * @param MenuItem $menuItem
     */
    public function deleteMenuItem(MenuItem $menuItem)
    {
        $this->deleteEntity($menuItem);
    }

    /**
     * Save a menu item
     *
     * @param MenuItem $menuItem
     * @param boolean $andFlush
     */
    public function saveMenuItem(MenuItem $menuItem, $andFlush = true)
    {
        $this->saveEntity($menuItem, $andFlush);
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
        $entities = $this->repository->findRootMenuItemsByMenu($menu, $criteria, $orderBy);

        $allEntities = array();
        foreach ($entities as $entity) {
            $allEntities[] = $entity;
            foreach ($entity->getChildren() as $entity) {
                $allEntities[] = $entity;
            }
        }
        $this->loadAcls($allEntities);

        return $entities;
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
        return $this->repository->findMenuItemByName($menu, $name);
    }
}