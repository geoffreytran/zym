<?php
/**
 * RAPP
 *
 * LICENSE
 *
 * This file is intellectual property of RAPP and may not
 * be used without permission.
 *
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
namespace Zym\Bundle\MenuBundle\Form\MenuItem;

use Zym\Bundle\MenuBundle\Form\MenuItemType;
use RAPP\Bundle\CMSBundle\Entity\MenuItemRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Routed Menu Item Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class RoutedMenuItemType extends MenuItemType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('uri', 'text', array(
            'disabled' => true
        ));

        $builder->add('route', 'text', array());
        //         ->add('routeParameters', 'collection', array(
        //             'type' => ''
        //         ));
    }
}