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
        $builder->addViewTransformer(new PermissionMaskTransformer());

        $builder->add('view', 'checkbox', array(
                    'label'       => 'View',
                    'help_block' => 'Whether someone is allowed to view the domain object.',
                    'required'    => false
                ))
                ->add('create', 'checkbox', array(
                    'label'       => 'Create',
                    'help_block' => 'Whether someone is allowed to create the domain object.',
                    'required'    => false
                ))
                ->add('edit', 'checkbox', array(
                    'label'       => 'Edit',
                    'help_block' => 'Whether someone is allowed to make changes to the domain object.',
                    'required'    => false
                ))
                ->add('delete', 'checkbox', array(
                    'label'       => 'Delete',
                    'help_block' => 'Whether someone is allowed to delete the domain object.',
                    'required'    => false
                ))
                ->add('undelete', 'checkbox', array(
                    'label'       => 'Undelete',
                    'help_block' => 'Whether someone is allowed to restore a previously deleted domain object.',
                    'required'    => false
                ))
                ->add('operator', 'checkbox', array(
                    'label'       => 'Operator',
                    'help_block' => 'Whether someone is allowed to perform all of the above actions.',
                    'required'    => false
                ))
                ->add('master', 'checkbox', array(
                    'label'       => 'Master',
                    'help_block' => 'Whether someone is allowed to perform all of the above actions, and in addition is allowed to grant any of the above permissions to others.',
                    'required'    => false
                ))
                ->add('iddqd', 'checkbox', array(
                    'label'       => 'IDDQD',
                    'help_block' => 'Whether someone owns the domain object. An owner can perform any of the above actions and grant master and owner permissions.',
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
        return 'zym_security_permission_mask';
    }
}