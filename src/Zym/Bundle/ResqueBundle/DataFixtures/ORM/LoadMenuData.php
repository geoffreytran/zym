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

namespace Zym\Bundle\ResqueBundle\DataFixtures\ORM;

use Zym\Bundle\MenuBundle\Entity\Menu;
use Zym\Bundle\MenuBundle\Entity\MenuItem;
use Zym\Bundle\MenuBundle\Entity\MenuItem\StaticMenuItem;
use Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem;
use Zym\Bundle\MenuBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

class LoadMenuData extends AbstractFixture
                   implements ContainerAwareInterface,
                              DependentFixtureInterface
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $container = $this->container;

        if (! array_key_exists('ZymMenuBundle', $container->getParameter('kernel.bundles'))) {
            return;
        }

        $menuItem = new RoutedMenuItem('queue');
        $menuItem->setLabel('Queue');
        $menuItem->setDescription('Manage the job queue.');
        $menuItem->setRoute('zym_resque');
        $menuItem->setWeight(90);

        $this->addMenuItem('management', $menuItem, 'system');
    }

    protected function addMenuItem($menu, MenuItem $menuItem, $parent = null)
    {
        $container = $this->container;

        if (!($container->has('zym_menu.menu_manager') && $container->has('knp_menu.factory'))) {
            // ZymMenuBundle doesn't exist
            return;
        }

        /* @var $menuManager Entity\MenuManager */
        $menuManager = $this->container->get('zym_menu.menu_manager');

        /* @var $menuItemManager Entity\MenuItemManager */
        $menuItemManager = $this->container->get('zym_menu.menu_item_manager');

        // Management Menu
        $menu = $menuManager->findOneBy(array(
            'name' => 'management'
        ));

        if ($menu === null) {
            return;
        }

        $existingMenuItem = $menuItemManager->findMenuItemByName($menu, $menuItem->getName());

        if ($existingMenuItem instanceof Entity\MenuItem) {
            return;
        }

        if ($parent !== null) {
            $parentMenuItem = $menuItemManager->findMenuItemByName($menu, $parent);

            if ($parentMenuItem === null) {
                return;
            }

            $parentMenuItem->addChild($menuItem);
            $menuItemManager->createMenuItem($menuItem);
            $menuItemManager->saveMenuItem($parentMenuItem);
        } else {
            $menu->addChild($menuItem);
            $menuItemManager->createMenuItem($menuItem);
            $menuManager->saveMenu($menu);
        }
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            'Zym\Bundle\MenuBundle\DataFixtures\ORM\LoadMenuData'
        );
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