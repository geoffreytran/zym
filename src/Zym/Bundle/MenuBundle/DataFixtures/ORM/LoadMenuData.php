<?php
namespace Zym\Bundle\MenuBundle\DataFixtures\ORM;

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

        $menu->setAttributes(array(
            'class' => 'nav'
        ));

        $menuManager->createMenu($menu);
        $menuManager->saveMenu($menu);

        $menuItem = new Entity\MenuItem\StaticMenuItem('home', $menuFactory);
        $menuItem->setLabel('Home');
        $menuItem->setUri('/');
        $manager->persist($menuItem);
        $menu->addChild($menuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('content', $menuFactory);
        $menuItem->setLabel('Content');
        $menuItem->setRoute('zym_nodes');
        $manager->persist($menuItem);
        $menu->addChild($menuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('users', $menuFactory);
        $menuItem->setLabel('Users');
        $menuItem->setRoute('zym_user_users');
        $manager->persist($menuItem);
        $menu->addChild($menuItem);

            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('groups', $menuFactory);
            $cMenuItem->setLabel('Groups');
            $cMenuItem->setRoute('zym_user_groups');
            $menuItem->addChild($cMenuItem);
            $manager->persist($menuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('structure', $menuFactory);
        $menuItem->setLabel('Structure');
        $menuItem->setRoute('zym_menu_categories');
        $menu->addChild($menuItem);

        $manager->persist($menuItem);
        $manager->flush();

        $menuItem->setRouteParameters(array('id' => $menuItem->getId()));
        $manager->persist($menuItem);

            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('content-types', $menuFactory);
            $cMenuItem->setLabel('Content Types');
            $cMenuItem->setRoute('zym_node_node_types');
            $menuItem->addChild($cMenuItem);

            $manager->persist($menuItem);

            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('menus', $menuFactory);
            $cMenuItem->setLabel('Menus');
            $cMenuItem->setRoute('zym_menus');
            $menuItem->addChild($cMenuItem);
            $manager->persist($menuItem);

        $menuItem = new Entity\MenuItem\RoutedMenuItem('configuration', $menuFactory);
        $menuItem->setLabel('Configuration');
        $menuItem->setRoute('zym_menu_categories');
        $menu->addChild($menuItem);

        $manager->persist($menuItem);
        $manager->flush();

        $menuItem->setRouteParameters(array('id' => $menuItem->getId()));
        $manager->persist($menuItem);

            $cMenuItem = new Entity\MenuItem\StaticMenuItem('Security', $menuFactory);
            $cMenuItem->setLabel('Security');
            $cMenuItem->setUri('/');
            $menuItem->addChild($cMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('permissions', $menuFactory);
                $ccMenuItem->setLabel('Permissions');
                $ccMenuItem->setRoute('zym_security_acl_entries');
                $cMenuItem->addChild($ccMenuItem);
                $manager->persist($ccMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('roles', $menuFactory);
                $ccMenuItem->setLabel('Roles');
                $ccMenuItem->setRoute('zym_security_acl_roles');
                $cMenuItem->addChild($ccMenuItem);
                $manager->persist($ccMenuItem);

                $ccMenuItem = new Entity\MenuItem\RoutedMenuItem('access-rules', $menuFactory);
                $ccMenuItem->setLabel('Access Rules');
                $ccMenuItem->setRoute('zym_security_access_rules');
                $cMenuItem->addChild($ccMenuItem);
                $manager->persist($ccMenuItem);

            $manager->persist($menuItem);

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
        return 1; // the order in which fixtures will be loaded
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