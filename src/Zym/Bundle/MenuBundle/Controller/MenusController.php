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

namespace Zym\Bundle\MenuBundle\Controller;

use Zym\Bundle\MenuBundle\Form;
use Zym\Bundle\MenuBundle\Entity;
use Zym\Bundle\MenuBundle\MenuItemEvents;
use Zym\Bundle\MenuBundle\Event;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class MenusController
 *
 * @package Zym\Bundle\MenuBundle\Controller
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class MenusController extends Controller
{
    /**
     * @Route(
     *     ".{_format}",
     *     name="zym_menus",
     *     defaults = { "_format" = "html" }
     * )
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        /* @var $menuManager Entity\MenuManager */
        $menuManager  = $this->get('zym_menu.menu_manager');
        $menus        = $menuManager->findMenus($filterBy, $page, $limit, $orderBy);

        return array(
            'menus' => $menus
        );
    }

    /**
     * @Route("/add", name="zym_menus_add")
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\Menu'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $menu = new Entity\Menu();
        $form = $this->createForm(new Form\MenuType(), $menu);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $menuManager Entity\MenuManager */
                $menuManager = $this->get('zym_menu.menu_manager');
                $menuManager->createMenu($menu);

                return $this->redirect($this->generateUrl('zym_menus'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Edit a menu
     *
     * @param Entity\Menu $menu
     *
     * @Route("/{id}/edit", name="zym_menus_edit")
     * @ParamConverter("menu", class="ZymMenuBundle:Menu")
     * @Template()
     *
     * @SecureParam(name="menu", permissions="EDIT")
     */
    public function editAction(Entity\Menu $menu)
    {
        $origMenu = clone $menu;
        $form         = $this->createForm(new Form\MenuType(), $menu);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $menuManager Entity\MenuManager */
                $menuManager = $this->get('zym_menu.menu_manager');
                $menuManager->saveMenu($menu);

                return $this->redirect($this->generateUrl('zym_menus'));
            }
        }

        return array(
            'menu' => $origMenu,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a menu
     *
     * @param Entity\Menu $menu
     *
     * @Route(
     *     "/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_menus_delete",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     *
     * @Template()
     *
     * @SecureParam(name="menu", permissions="DELETE")
     */
    public function deleteAction(Entity\Menu $menu)
    {
        $origMenu = clone $menu;

        /* @var $menuManager Entity\MenuManager */
        $menuManager = $this->get('zym_menu.menu_manager');
        $form        = $this->createForm(new Form\DeleteType(), $menu);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $menuManager->deleteMenu($menu);

                return $this->redirect($this->generateUrl('zym_menus'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $menuManager->deleteMenu($menu);

            return $this->redirect($this->generateUrl('zym_menus'));
        }

        return array(
            'menu' => $origMenu,
            'form' => $form->createView()
        );
    }

    /**
     * Show a menu
     *
     * @param Entity\Menu $menu
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name     ="zym_menus_show",
     *     defaults = { "_format" = "html" }
     * )
     * @ParamConverter("menu", class="ZymMenuBundle:Menu")
     * @Template()
     *
     * @SecureParam(name="menu", permissions="EDIT")
     */
    public function showAction(Entity\Menu $menu)
    {
        $menuItemManager = $this->get('zym_menu.menu_item_manager');
        $menuItems       = $menuItemManager->findRootMenuItemsByMenu($menu);

        $menu->setChildren($menuItems);

        return array(
            'menu' => $menu
        );
    }

    /**
     * Add a menu item
     *
     * @param Entity\Menu $menu
     *
     * @Route(
     *     "/{id}/add/{type}",
     *     name     = "zym_menus_item_add",
     *     defaults = {
     *         "type" = null
     *     }
     * )
     * @ParamConverter("menu", class="ZymMenuBundle:Menu")
     * @Template()
     *
     * @SecureParam(name="menu", permissions="EDIT")
     */
    public function addMenuItemAction(Entity\Menu $menu, $type = null)
    {
       $securityContext = $this->get('security.context');

       // check for create access
       if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\MenuItem'))) {
           throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
       }

       if ($type) {

            switch ($type) {
                case 'routed':
                    $menuItem = new Entity\MenuItem\RoutedMenuItem(null, $this->get('knp_menu.factory'), $menu);
                    $menuItem->setRouter($this->get('router'));

                    $form     = $this->createForm(new Form\MenuItem\RoutedMenuItemType(), $menuItem);
                    break;

                case 'static':
                    $menuItem = new Entity\MenuItem\StaticMenuItem(null, $this->get('knp_menu.factory'), $menu);
                    $form     = $this->createForm(new Form\MenuItem\StaticMenuItemType(), $menuItem);
                    break;

                case 'section':
                    $menuItem = new Entity\MenuItem\SectionMenuItem(null, $this->get('knp_menu.factory'), $menu);
                    $form     = $this->createForm(new Form\MenuItem\SectionMenuItemType(), $menuItem);
                    break;

                default:
                    throw $this->createNotFoundException('Invalid MenuItem Type');
            }

            $request = $this->get('request');
            if ($request->getMethod() == 'POST') {
                $form->bind($request);

                if ($form->isValid()) {
                    $menuItemManager = $this->get('zym_menu.menu_item_manager');
                    $menuItemManager->createMenuItem($menuItem);

                    return $this->redirect($this->generateUrl('zym_menus_show', array('id' => $menu->getName())));
                }
            }

            return array(
                'menu' => $menu,
                'form' => $form->createView(),
                'type' => $type
            );
        }
    }


    /**
     * Edit a menu item
     *
     * @param Entity\Menu $menu
     *
     * @Route("/{menu}/{id}/edit", name="zym_menus_item_edit")
     * @Template()
     *
     * @SecureParam(name="menu", permissions="EDIT")
     */
    public function editMenuItemAction($menu, Entity\MenuItem $menuItem)
    {
        $menu         = $menuItem->getMenu();
        $origMenuItem = clone $menuItem;

        switch (get_class($menuItem)) {
            case 'Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem':
                $menuItem->setRouter($this->get('router'));
                $form     = $this->createForm(new Form\MenuItem\RoutedMenuItemType(), $menuItem);
                break;

            case 'Zym\Bundle\MenuBundle\Entity\MenuItem\StaticMenuItem':
                $form     = $this->createForm(new Form\MenuItem\StaticMenuItemType(), $menuItem);
                break;

            default:
                throw $this->createNotFoundException('Invalid MenuItem Type');
        }

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $menuItemManager = $this->get('zym_menu.menu_item_manager');
                $menuItemManager->saveMenuItem($menuItem);

                return $this->redirect($this->generateUrl('zym_menus_show', array('id' => $menu->getName())));
            }
        }

        return array(
            'menu'     => $menu,
            'menuItem' => $origMenuItem,
            'form'     => $form->createView()
        );
    }

    /**
     * Delete a menu item
     *
     * @param Entity\Menu $menu
     *
     * @Route(
     *     "/{menu}/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route("/{menu}/{id}/delete", name="zym_menus_item_delete")
     *
     * @Template()
     *
     * @SecureParam(name="menu", permissions="EDIT")
     */
    public function deleteMenuItemAction($menu, Entity\MenuItem $menuItem)
    {
        $menu = $menuItem->getMenu();

        /* @var $menuItemManager Entity\MenuItemManager */
        $menuItemManager = $this->get('zym_menu.menu_item_manager');

        $form        = $this->createForm(new Form\DeleteType(), $menuItem);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $menuItemManager->deleteMenuItem($menuItem);

                return $this->redirect($this->generateUrl('zym_menus_show', array('id' => $menu->getName())));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $menuItemManager->deleteMenuItem($menuItem);

            return $this->redirect($this->generateUrl('zym_menus_show', array('id' => $menu->getName())));
        }

        return array(
            'menu'     => $menu,
            'menuItem' => $menuItem,
            'form'     => $form->createView()
        );
    }
}