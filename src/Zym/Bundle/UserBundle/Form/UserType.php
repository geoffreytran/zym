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
namespace Zym\Bundle\UserBundle\Form;

use Zym\Bundle\UserBundle\Model;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * User Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class UserType extends AbstractType
{
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
            ->add('roles', 'acl_security_identity_entity')
            ->add('groups', 'entity', array(
                'class'    => 'ZymUserBundle:Group',
                'property' => 'name',
                'multiple' => true,
                'required' => false
            ));
        
        $builder->add('timeZone', 'timezone', array(
            'label'       => 'Time Zone',
            'empty_value' => 'Choose your time zone',
            'empty_data'  => null,
            'required'    => false
        ));
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