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

namespace Zym\Bundle\UserBundle\Form;

use Zym\Bundle\UserBundle\Model;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UserType
 *
 * @package Zym\Bundle\UserBundle\Form
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['data'] instanceof Model\SplitNameInterface) {
            $builder
                ->add('firstName', 'text', array('label' => 'First Name'))
                ->add('middleName', 'text', array(
                    'label'    => 'Middle Name',
                    'required' => false,
                ))
                ->add('lastName', 'text', array('label' => 'Last Name'));
        } else if ($options['data'] instanceof Model\SingleNameInterface) {
            $builder
                ->add('name', 'text', array('label' => 'Name'));

        }
        
        if ($options['data']->getUsernameCanonical() != $options['data']->getEmailCanonical()) {
            $builder->add('username');
        }
        
        $builder
            ->add('email', 'email')
            ->add('plainPassword', 'repeated', array(
                'type'            => 'password',
                'first_name'      =>'password',
                'second_name'     =>'confirmPassword',
                'second_options'  => array('label' => 'Confirm Password'),
                'error_bubbling'  => true,
                'invalid_message' => 'Passwords do not match'
            ))
            ->add('roles', 'acl_security_identity_entity', array(
                'attr'     => array(
                    'placeholder' => 'Choose your roles.'
                )
            ))
            ->add('groups', 'entity', array(
                'class'    => 'ZymUserBundle:Group',
                'property' => 'name',
                'multiple' => true,
                'required' => false,
                'attr'     => array(
                    'placeholder' => 'Choose your groups.'
                )
            ))
        ;

        if ($options['data'] instanceof Model\TimeZoneInterface) {
            $builder
                ->add('timeZone', 'timezone', array(
                    'label'       => 'Time Zone',
                    'empty_value' => 'Choose your time zone.',
                    'empty_data'  => null,
                    'required'    => false
                ))
            ;
        }

        $builder
            ->add('enabled', 'checkbox', array(
                'label'       => 'Enabled',
                'help_block'  => 'Whether user is enabled.',
                'help_widget_popover' => array(
                    'title' => 'help popover text',
                    'content' => 'beautiful, isn\'t it?'
                ),
                'required' => false
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
        return 'zym_user_user';
    }
}