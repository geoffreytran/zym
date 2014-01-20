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

namespace Zym\Bundle\MenuBundle\Event;

use Knp\Menu\MenuItem;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormTypeInterface;

class CreateMenuItemFormEvent extends Event
{
    private $menuItem;
    private $formType;

    public function __construct(MenuItem $menuItem)
    {
        $this->menuItem = $menuItem;
    }

    public function getMenuItem()
    {
        return $this->menuItem;
    }

    public function setMenuItem(MenuItem $menuItem)
    {
        $this->menuItem = $menuItem;
    }

    public function getFormType()
    {
        return $this->formType;
    }

    public function setFormType(FormTypeInterface $formType)
    {
        $this->formType = $formType;
    }
}