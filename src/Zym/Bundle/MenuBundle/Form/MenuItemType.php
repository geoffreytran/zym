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

namespace Zym\Bundle\MenuBundle\Form;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Menu Item Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class MenuItemType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label'       => 'Name',
                'help_block' => 'Name must be unique to the menu.'
            ))
            ->add('label', 'text', array('label' => 'Label'))
            ->add('description', 'text', array('label' => 'Description'))
            ->add('weight', 'integer')
            ->add('parent', 'menu_item_entity', array(
                'label'         => 'Parent',
                'required'      => false,
                'class'         => 'ZymMenuBundle:MenuItem',
                'property'      => 'label',
                'query_builder' => function(ObjectRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('mi');
                    $qb->where('mi.menu = :menu');

                    $qb->setParameter('menu', $options['data']->getMenu());
                    return $qb;
                }
            ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_menu_menu_item';
    }
}