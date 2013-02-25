<?php
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