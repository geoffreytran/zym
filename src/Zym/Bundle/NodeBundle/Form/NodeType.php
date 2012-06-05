<?php
namespace Zym\Bundle\NodeBundle\Form;

use Zym\Bundle\NodeBundle\Entity;
use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NodeType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $node Entity\Node */
        $node = $builder->getData();

        if (!$node instanceof Entity\Node) {
            throw new \InvalidArgumentException(
                'Data for form must be of type Zym\Bundle\NodeBundle\Entity\Node'
            );
        }

        $builder->add('title', 'text');

        foreach ($node->getFieldConfigs() as $fieldConfig) {
            /* @var $field FieldConfig */
            $machineName = $fieldConfig->getFieldType()->getMachineName();
            $builder->add($machineName, $fieldConfig, array('property_path' => 'fields.' . $machineName . '.data'));
        }
    }

    /**
     * Get the default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Zym\Bundle\NodeBundle\Entity\Node',
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'node';
    }
}