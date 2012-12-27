<?php
namespace Zym\Bundle\MenuBundle\DataFixtures\ORM;

use Zym\Bundle\MenuBundle\Entity\Menu;
use Zym\Bundle\MenuBundle\Entity\MenuItem\StaticMenuItem;
use Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem;
use Zym\Bundle\MenuBundle\Entity;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

class LoadMenuData extends AbstractFixture
                   implements ContainerAwareInterface, OrderedFixtureInterface
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
        $menuManager = $this->container->get('zym_menu.menu_manager');
        $menuFactory = $this->container->get('knp_menu.factory');

        $menuItemManager = $this->container->get('zym_menu.menu_item_manager');

        // Main Menu
        $menu = new Entity\Menu();
        $menu->setName('main');
        $menu->setLabel('Main Menu');
        $menu->setDescription('The Main menu\'s links drive the main navigation structure for your site, and are often displayed prominently across the top or side of the site.');

        $menuItem = new Entity\MenuItem\StaticMenuItem('home', $menuFactory);
        $menuItem->setLabel('Home');
        $menuItem->setUri('/');
        $menu->addChild($menuItem);

        $menuManager->createMenu($menu);
        $menuManager->saveMenu($menu);

        // Management Menu
        $menu = new Entity\Menu();
        $menu->setName('management');
        $menu->setLabel('Management Menu');
        $menu->setDescription('The Management menu contains links for administrative tasks.');

        $menuManager->createMenu($menu);

        $menuItem = new Entity\MenuItem\StaticMenuItem('home', $menuFactory);
        $menuItem->setLabel('Home');
        $menuItem->setUri('/');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);


        $menuItem = new Entity\MenuItem\RoutedMenuItem('content', $menuFactory);
        $menuItem->setLabel('Content');
        $menuItem->setRoute('zym_nodes');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('users', $menuFactory);
        $menuItem->setLabel('Users');
        $menuItem->setRoute('zym_user_users');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);

            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('groups', $menuFactory);
            $cMenuItem->setLabel('Groups');
            $cMenuItem->setRoute('zym_user_groups');
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('structure', $menuFactory);
        $menuItem->setLabel('Structure');
        $menuItem->setRoute('zym_menu_categories');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);
        $menuItem->setRouteParameters(array('id' => $menuItem->getId()));
        $menuItemManager->saveMenuItem($menuItem);


            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('content-types', $menuFactory);
            $cMenuItem->setLabel('Content Types');
            $cMenuItem->setRoute('zym_node_node_types');
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);


            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('menus', $menuFactory);
            $cMenuItem->setLabel('Menus');
            $cMenuItem->setRoute('zym_menus');
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('theme-rules', $menuFactory);
            $cMenuItem->setLabel('Themes Rules');
            $cMenuItem->setRoute('zym_theme_theme_rules');
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('configuration', $menuFactory);
        $menuItem->setLabel('Configuration');
        $menuItem->setRoute('zym_menu_categories');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);
        $menuItem->setRouteParameters(array('id' => $menuItem->getId()));
        $menuItemManager->saveMenuItem($menuItem);

            $cMenuItem = new Entity\MenuItem\StaticMenuItem('security', $menuFactory);
            $cMenuItem->setLabel('Security');
            $cMenuItem->setUri('/');
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('permissions', $menuFactory);
                $ccMenuItem->setLabel('Permissions');
                $ccMenuItem->setRoute('zym_security_acl_entries');
                $cMenuItem->addChild($ccMenuItem);
                $menuItemManager->createMenuItem($ccMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('roles', $menuFactory);
                $ccMenuItem->setLabel('Roles');
                $ccMenuItem->setRoute('zym_security_acl_roles');
                $cMenuItem->addChild($ccMenuItem);
                $menuItemManager->createMenuItem($ccMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('access-rules', $menuFactory);
                $ccMenuItem->setLabel('Access Rules');
                $ccMenuItem->setRoute('zym_security_access_rules');
                $cMenuItem->addChild($ccMenuItem);
                $menuItemManager->createMenuItem($ccMenuItem);

            $cMenuItem = new Entity\MenuItem\StaticMenuItem('system', $menuFactory);
            $cMenuItem->setLabel('System');
            $cMenuItem->setUri('/');
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('mail', $menuFactory);
                $ccMenuItem->setLabel('Mail');
                $ccMenuItem->setRoute('zym_mail_config_edit');
                $cMenuItem->addChild($ccMenuItem);
                $menuItemManager->createMenuItem($ccMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('parameters', $menuFactory);
                $ccMenuItem->setLabel('Parameters');
                $ccMenuItem->setRoute('zym_runtime_config_parameters');
                $cMenuItem->addChild($ccMenuItem);
                $menuItemManager->createMenuItem($ccMenuItem);

        $menuManager->saveMenu($menu);
        $manager->flush();
    }

    /**
     * Get the order in which fixtures will be loaded
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10; // the order in which fixtures will be loaded
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