<?php
namespace Zym\Bundle\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PermissionMaskType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->appendClientTransformer(new PermissionMaskTransformer());

        $builder->add('view', 'checkbox', array(
                    'label'    => 'View',
                    'required' => false
                ))
                ->add('create', 'checkbox', array(
                    'label' => 'Create',
                    'required' => false
                ))
                ->add('edit', 'checkbox', array(
                    'label' => 'Edit',
                    'required' => false
                ))
                ->add('delete', 'checkbox', array(
                    'label' => 'Delete',
                    'required' => false
                ))
                ->add('undelete', 'checkbox', array(
                    'label' => 'Undelete',
                    'required' => false
                ))
                ->add('operator', 'checkbox', array(
                    'label' => 'Operator',
                    'required' => false
                ))
                ->add('master', 'checkbox', array(
                    'label' => 'Master',
                    'required' => false
                ))
                ->add('iddqd', 'checkbox', array(
                    'label' => 'IDDQD',
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
        return 'zym_security_permission_mask';
    }
}