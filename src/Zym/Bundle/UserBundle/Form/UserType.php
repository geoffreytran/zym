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

use Zym\Bundle\SecurityBundle\Form\AclSecurityIdentityEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * User Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array('label' => 'First Name'))
            ->add('lastName', 'text', array('label' => 'Last Name'))
            ->add('email', 'email')
            ->add('plainPassword', 'repeated', array(
                'type'        => 'password',
                'first_name'  =>'Password',
                'second_name' =>'Confirm Password',
            ))
            ->add('roles', new AclSecurityIdentityEntityType())
            ->add('groups', 'entity', array(
                'class'    => 'Zym\Bundle\UserBundle\Entity\Group',
                'property' => 'name',
                'multiple' => true,
                'required' => false
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