<?php
namespace Zym\Bundle\FieldBundle\Form;

use Zym\Bundle\CMSBundle\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class FieldConfigsType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        /* @var $node Entity\Node */
        $node = $builder->getData();

        if (!$node instanceof Entity\Node) {
            throw new \InvalidArgumentException(
                'Data for form must be of type Zym\Bundle\CMSBundle\Entity\Node'
            );
        }

        $builder->add('title', 'text');

        foreach ($node->getFields() as $field) {
            /* @var $field Entity\NodeField */

            $builder->add($field->getName(), $field);
        }
    }

    public function getDefaultOptions()
    {
        return array(
            'data_class' => 'Acme\TaskBundle\Entity\Category',
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'field_configs';
    }
}