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

namespace Zym\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PageType
 *
 * @package Zym\Bundle\ContentBundle\Form
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class PageType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parent', 'phpcr_document', array(
                    'class'    => 'Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page',
                    'property' => 'path'
                ))
                ->add('name', 'text')
                ->add('label', null, array('required' => false))
                ->add('title')
                ->add('createDate', 'datetime', array(
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text'
                ))
                ->add('publishable', 'checkbox', array(
                ))
                ->add('publishStartDate', 'date', array(
                    'widget'     => 'single_text',
                    'required'   => false,
                    'help_block' => 'Only publish on or after this date, leave empty to immediately publish.'
                ))
                ->add('publishEndDate', 'date', array(
                    'widget'     => 'single_text',
                    'required'   => false,
                    'help_block' => 'Only publish before this date, leave empty to not end publishing.'
                ))
                ->add('body', 'textarea', array(
                    'attr' => array(
                        'rows' => 20
                    )
                ))
                ->add('extras', 'collection', array(
                    'type'         => 'text',
                    'allow_add'    => true,
                    'allow_delete' => true, // should render default button, change text with widget_remove_btn
                    'prototype'    => true,
                    'options'      => array( // options for collection fields
                        'horizontal'                     => true,
                        'label_render'                   => false,
                        'horizontal_input_wrapper_class' => "col-lg-8",
                    )
                ))
        ;

    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_content_page';
    }
}