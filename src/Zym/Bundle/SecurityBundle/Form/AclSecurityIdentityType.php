<?php
namespace Zym\Bundle\SecurityBundle\Form;

use Zym\Bundle\SecurityBundle\Entity;
use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AclSecurityIdentityType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('identifier', 'text');
        $builder->add('username', 'checkbox', array(
            'label'     => 'Represents a User?',
            'required'  => false,
        ));
        
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Zym\Bundle\SecurityBundle\Entity\AclSecurityIdentity',
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