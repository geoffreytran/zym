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
                   implements ContainerAwareInterface
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
        $menuManager->createMenu($menu);

        $menuItem = new Entity\MenuItem\StaticMenuItem('home', $menuFactory);
        $menuItem->setLabel('Home');
        $menuItem->setUri('/');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);

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

        $menuItem = new Entity\MenuItem\SectionMenuItem('reports', $menuFactory);
        $menuItem->setLabel('Reports');
        $menuItem->setDescription('Manage the reporting application.');
        $menuItem->setWeight(80);
        $menuItem->setUri('admin/reports');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);

        $menuItem = new Entity\MenuItem\SectionMenuItem('structure', $menuFactory);
        $menuItem->setLabel('Structure');
        $menuItem->setDescription('Manage the structure for application.');
        $menuItem->setWeight(100);
        $menuItem->setUri('admin/structure');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);

            $cMenuItem = new Entity\MenuItem\RoutedMenuItem('menus', $menuFactory);
            $cMenuItem->setLabel('Menus');
            $cMenuItem->setDescription('Manage the structure of menus.');
            $cMenuItem->setRoute('zym_menus');
            $cMenuItem->setWeight(30);
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

        $menuItem = new Entity\MenuItem\SectionMenuItem('configuration', $menuFactory);
        $menuItem->setLabel('Configuration');
        $menuItem->setDescription('Manage the configuration for the application.');
        $menuItem->setWeight(500);
        $menuItem->setUri('admin/config');
        $menu->addChild($menuItem);
        $menuItemManager->createMenuItem($menuItem);

            $cMenuItem = new Entity\MenuItem\StaticMenuItem('security', $menuFactory);
            $cMenuItem->setLabel('Security');
            $cMenuItem->setDescription('Manage the security for the application.');
            $cMenuItem->setUri('/');
            $cMenuItem->setWeight(70);
            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);

            $cMenuItem = new Entity\MenuItem\StaticMenuItem('system', $menuFactory);
            $cMenuItem->setLabel('System');
            $cMenuItem->setDescription('Manage the system settings for the application.');
            $cMenuItem->setUri('/');
            $cMenuItem->setWeight(80);

            $menuItem->addChild($cMenuItem);
            $menuItemManager->createMenuItem($cMenuItem);


        $menuManager->saveMenu($menu);
        $manager->flush();
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