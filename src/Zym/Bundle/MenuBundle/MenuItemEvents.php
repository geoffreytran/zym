<?php

namespace Zym\Bundle\MenuBundle;

final class MenuItemEvents
{
    /**
     * The zym_menu.menu_item.create_form event is thrown each time a menu item form
     * is created.
     *
     * The event listener receives an
     * Zym\Bundle\MenuBundle\Event\CreateMenuItemFormEvent instance.
     *
     * @var string
     */
    const CREATE_FORM = 'zym_menu.menu_item.create_form';
}