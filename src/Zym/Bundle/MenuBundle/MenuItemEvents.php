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