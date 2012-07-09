<?php
namespace Zym\Bundle\RuntimeConfigBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('value', new ParameterValueType());
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Zym\Bundle\RuntimeConfigBundle\Entity\Parameter',
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_runtime_config_parameter';
    }
}