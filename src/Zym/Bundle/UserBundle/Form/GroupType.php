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
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Group Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('roles', 'acl_security_identity_entity', array(
                'class'             => 'ZymSecurityBundle:AclSecurityIdentity',
                'property'          => 'identifier',
                'multiple'          => true,
                'data_class'        => 'Zym\\Bundle\\SecurityBundle\\Entity\\AclSecurityIdentity',
                'query_builder'     => function(EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                              ->where('r.username = 0');
            }));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_user_group';
    }
}