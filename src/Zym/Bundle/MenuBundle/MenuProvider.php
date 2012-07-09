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

namespace Zym\Bundle\MenuBundle;

use Zym\Bundle\MenuBundle\Entity;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Knp\Menu\Provider\MenuProviderInterface;

/**
 * RAPP CMS Bundle
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class MenuProvider implements MenuProviderInterface, ContainerAwareInterface
{
    /**
     * Menu Factory
     *
     * @var MenuBundle\MenuFactory
     */
    protected $menuFactory;

    /**
     * Menu Manager
     *
     * @var Entity\MenuManager
     */
    protected $menuManager;

    /**
     * Menu Item Manager
     *
     * @var Entity\MenuItemManager
     */
    protected $menuItemManager;

    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected $container;
    
    protected $menus = array();

    /**
     * Construct
     *
     * @param Entity\MenuManager $menuManager
     * @param Entity\MenuItemManager $menuItemManager
     * @param ContainerInterface $container
     */
    public function __construct(Entity\MenuManager $menuManager,
                                Entity\MenuItemManager $menuItemManager,
                                ContainerInterface $container)
    {
        $this->menuManager     = $menuManager;
        $this->menuItemManager = $menuItemManager;
        $this->container       = $container;
    }

    /**
     * Retrieves a menu by its name
     *
     * @param string $name
     * @param array  $options
     * @return \Knp\Menu\ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function get($name, array $options = array())
    {
        if (isset($this->menus[$name])) {
            return $this->menus[$name];
        }
        
        $menuManager     = $this->getMenuManager();
        $menuItemManager = $this->getMenuItemManager();

        $menu      = $menuManager->findMenuBy(array('name' => $name));
        $menuItems = $menuItemManager->findRootMenuItemsByMenu($menu);

        $menu->setChildren($menuItems);

        $router  = $this->container->get('router');
        $request = $this->container->get('request');

        foreach ($menu->getChildren() as $child) {
            $child->setRouter($router);
        }

        $this->menus[$name] = $menu;
        return $menu;
    }
    
    /**
     * Checks whether a menu exists in this provider
     *
     * @param string $name
     * @param array  $options
     * @return bool
     */
    public function has($name, array $options = array())
    {
        if (isset($this->menus[$name])) {
            return true;
        }
        
        $menuManager = $this->getMenuManager();
        $menu        = $menuManager->hasMenuBy(array('name' => $name));
        return (bool) $menu;
    }

    /**
     * Get the menu manager
     *
     * @return Entity\MenuManager
     */
    public function getMenuManager()
    {
        return $this->menuManager;
    }

    /**
     * Set the menu manager
     *
     * @param Entity\MenuManager $menuManager
     * @return MenuProvider
     */
    public function setMenuManager(Entity\MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
        return $this;
    }

    /**
     * Get the menu item manager
     *
     * @return Entity\MenuItemManager
     */
    public function getMenuItemManager()
    {
        return $this->menuItemManager;
    }

    /**
     * Set the menu item manager
     *
     * @param Entity\MenuItemManager $menuItemManager
     * @return MenuProvider
     */
    public function setMenuItemManager(Entity\MenuItemManager $menuItemManager)
    {
        $this->menuItemManager = $menuItemManager;
        return $this;
    }
    
    /**
     * Set the container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}