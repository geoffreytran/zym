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

namespace Zym\Bundle\SecurityBundle\Form;

use Zym\Bundle\SecurityBundle\Entity;
use Symfony\Component\Security\Acl\Domain\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AclEntryType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('securityIdentity', 'acl_security_identity_entity', array(
                    'label'         => 'Security Identity',
                    'property_path' => ($options['data'] instanceof Entry) ? 'securityIdentity.role' : 'securityIdentity',
                    'multiple'      => false,
                    'read_only'     => ($options['data'] instanceof Entry)
                ))
                ->add('mask', new PermissionMaskType(), array(
                    'label' => 'Permission Mask'
                ))
                ->add('granting', 'choice', array(
                    'choices'           => array('Deny', 'Allow'),
                    'read_only'         => true
                ))
                ->add('strategy', 'choice', array(
                    'choices'           => array('equal' => 'Equal', 'all' => 'All', 'any' => 'Any'),
                    'preferred_choices' => array('all')
                ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_security_acl_security_identity';
    }
}