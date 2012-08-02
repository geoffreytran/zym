<?php
namespace Zym\Bundle\NodeBundle\Form;

use Zym\Bundle\NodeBundle\Entity;
use Zym\Bundle\FieldBundle\Form\FieldCollectionItemType;
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
            $valueCount  = $fieldConfig->getFieldType()->getValueCount();
            
            if ($valueCount > 1) {
                $builder->add($machineName, 'collection', array(
                    'type'          => new FieldCollectionItemType($fieldConfig),
                    'property_path' => 'fields.' . $machineName
                ));
            } else {
                $builder->add($machineName, $fieldConfig, array('property_path' => 'fields.' . $machineName . '.data'));
            }
        }
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