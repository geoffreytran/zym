<?php
namespace Zym\Bundle\SecurityBundle\Form;

use Zym\Bundle\SecurityBundle\Entity;
use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AclEntryType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        
        $builder->add('securityIdentity', 'text', array(
                    'label' => 'SecurityIdentity',
                    'property_path' => 'securityIdentity.role',
                    'read_only' => true
                ))
                ->add('mask', new PermissionMaskType(), array(
                    'label' => 'Permission Mask'
                ))
                ->add('granting', 'choice', array(
                    'choices'           => array('No', 'Yes'),
                    'preferred_choices' => array(1),
                    'read_only' => true
                ))
                ->add('strategy', 'choice', array(
                    'choices'           => array('equal' => 'Equal', 'all' => 'All', 'any' => 'Any'),
                    'preferred_choices' => array('all')
                ));;
        
        
    }

    public function getDefaultOptions(array $options)
    {
        return array(
        );
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