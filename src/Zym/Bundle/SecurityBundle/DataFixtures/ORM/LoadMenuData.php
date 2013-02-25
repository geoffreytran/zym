<?php
namespace Zym\Bundle\SecurityBundle\DataFixtures\ORM;

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

        $menuItem = new RoutedMenuItem('permissions');
        $menuItem->setLabel('Permissions');
        $menuItem->setDescription('Manage what users are able to do.');
        $menuItem->setRoute('zym_security_acl_entries');
        $menuItem->setWeight(30);

        $this->addMenuItem('management', $menuItem, 'security');

        $menuItem = new RoutedMenuItem('roles');
        $menuItem->setLabel('Roles');
        $menuItem->setDescription('Manage roles users can be assigned to.');
        $menuItem->setRoute('zym_security_acl_roles');
        $menuItem->setWeight(40);

        $this->addMenuItem('management', $menuItem, 'security');

        $menuItem = new RoutedMenuItem('access-rules');
        $menuItem->setLabel('Access Rules / Firewall');
        $menuItem->setDescription('Manage access by the URL path.');
        $menuItem->setRoute('zym_security_access_rules');
        $menuItem->setWeight(60);

        $this->addMenuItem('management', $menuItem, 'security');
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