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

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

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
        $builder
            ->add('firstName', 'text', array('label' => 'First Name'))
            ->add('lastName', 'text', array('label' => 'Last Name'))
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
                'class'             => 'ZymSecurityBundle:AclSecurityIdentity',
                'property'          => 'identifier',
                'multiple'          => true,
                'data_class'        => 'Zym\\Bundle\\SecurityBundle\\Entity\\AclSecurityIdentity',
                'query_builder'     => function(EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                              ->where('r.username = 0');
                },
               // 'value_strategy'    => ChoiceList::COPY_CHOICE,
                //'index_strategy'    => ChoiceList::COPY_CHOICE,
            ))
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