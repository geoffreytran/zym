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

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;

use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Item Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
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
        return $this->repository->findRootMenuItemsByMenu($menu, $criteria, $orderBy);
    }
}