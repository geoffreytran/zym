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
                ))
                ->add('create', 'checkbox', array(
                    'label' => 'Create',
                ))
                ->add('edit', 'checkbox', array(
                    'label' => 'Edit',
                ))
                ->add('delete', 'checkbox', array(
                    'label' => 'Delete',
                ))
                ->add('undelete', 'checkbox', array(
                    'label' => 'Undelete',
                ))
                ->add('operator', 'checkbox', array(
                    'label' => 'Operator',
                ))
                ->add('master', 'checkbox', array(
                    'label' => 'Master',
                ))
                ->add('iddqd', 'checkbox', array(
                    'label' => 'IDDQD',
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