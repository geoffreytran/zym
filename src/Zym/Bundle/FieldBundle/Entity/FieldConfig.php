<?php

namespace Zym\Bundle\FieldBundle\Entity;

use Zym\Bundle\FieldBundle\FieldConfigInterface;
use Zym\Bundle\FieldBundle\FieldTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\FieldType as FormFieldType;
use Symfony\Component\Form\AbstractType as FormAbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * @ORM\Entity()
 * @ORM\Table(name="field_configs")
 */
class FieldConfig extends    FormFieldType 
                  implements FieldConfigInterface
{
    /**
     * ID
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Field Type
     *
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="FieldType", fetch="EAGER")
     * @ORM\JoinColumn(name="field_type", referencedColumnName="machine_name", nullable=false, onDelete="CASCADE")
     */
    protected $fieldType;
    
    /**
     * Label
     *
     * @var string
     *
     * @ORM\Column(type="string") 
     */
    protected $label;
    
    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;
    
    /**
     * Widget
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $widget;
    
    /**
     * Field Widget Options
     *
     * @var array
     *
     * @ORM\Column(type="array", name="widget_options")
     */
    protected $widgetOptions = array();
    
    /**
     * Required
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $required = false;
    
    /**
     * Weight
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $weight = 0;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getFieldType()
    {
        return $this->fieldType;
    }
    
    public function setFieldType(FieldTypeInterface $fieldType)
    {
        $this->fieldType = $fieldType;
        return $this;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getWidget()
    {
        return $this->widget;
    }
    
    public function setWidget($widget)
    {
        $this->widget = $widget;
        return $this;
    }
    
    public function getWidgetOptions()
    {
        return $this->widgetOptions;
    }
    
    public function setWidgetOptions(array $options)
    {
        $this->widgetOptions = $options;
        return $this;
    }
    
    public function isRequired()
    {
        return $this->required;
    }
    
    public function setRequired($required)
    {
        $this->required = (bool) $required;
        return $this;
    }
    
    public function getWeight()
    {
        return $this->weight;
    }
    
    public function setWeight($weight)
    {
        $this->weight = (int) $weight;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $valueCount = $this->fieldType->getValueCount();
        
    }

    /**
     * {@inheritdoc}
     */
    public function getAdllowedOptionValues(array $options)
    {
        return array(
            'type' => array(
                'fractional',
                'integer',
            ),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        $defaultOptions = array(
            'required'          => $this->required,
            'label'             => $this->label
        );
        
        $defaultOptions = array_merge(
            parent::getDefaultOptions(array()),
            $defaultOptions,
            $this->getWidgetOptions()
        );

        return $defaultOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return $this->widget;
    }
    
    /**
     * Get the field name
     *
     * @return string
     */
    public function getName()
    {
        return 'field_' . $this->fieldType->getMachineName();
    }
}