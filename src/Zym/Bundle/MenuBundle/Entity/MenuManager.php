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
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Menu Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class MenuManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var MenuRepository
     */
    protected $repository;

    /*
     * Create a menu
     *
     * @param Menu $menu
     * @return Menu
     */
    public function createMenu(Menu $menu)
    {
        parent::createEntity($menu);

        return $menu;
    }

    /**
     * Delete a menu
     *
     * @param Menu $menu
     */
    public function deleteMenu(Menu $menu)
    {
        parent::deleteEntity($menu);
    }

    /**
     * Save a menu
     *
     * @param Menu $menu
     * @param boolean $andFlush
     */
    public function saveMenu(Menu $menu, $andFlush = true)
    {
        $this->saveEntity($menu, $andFlush);
    }

    /**
     * Find a menu by criteria
     *
     * @param array $criteria
     * @return Menu
     */
    public function findMenuBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Whether a menu exists
     *
     * @param array $criteria
     * @return boolean
     */
    public function hasMenuBy(array $criteria)
    {
        return $this->repository->hasMenuBy($criteria);
    }

    /**
     * Find all menus by criteria
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findMenus(array $criteria = null, $page = 1, $limit = 10, array $orderBy = null)
    {
        $entities = $this->repository->findMenus($criteria, $page, $limit, $orderBy);
        $this->loadAcls($entities);
        return $entities;
    }
}