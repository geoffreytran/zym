<?php
namespace Zym\Bundle\FieldBundle\Form;

use Zym\Bundle\FieldBundle\FieldConfigInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FieldCollectionItemType extends AbstractType
{   
    
    private $fieldConfig;
    
    public function __construct(FieldConfigInterface $fieldConfig)
    {
        $this->fieldConfig = $fieldConfig;
    }
    
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {      
        $builder->resetClientTransformers();
        $builder->appendClientTransformer(new ValueToTypeTransformer($fieldConfig));        
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->fieldConfig->getWidget();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zym_field_collection_item';
    }
}