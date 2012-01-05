<?php
namespace Zym\Bundle\FieldBundle;

interface FieldConfigInterface
{
    public function getFieldType();
    public function setFieldType(FieldTypeInterface $fieldType);
    
    public function getLabel();
    public function setLabel($label);
    
    public function getDescription();
    public function setDescription($description);
    
    public function getWidget();
    public function setWidget($widget);
    
    public function getWidgetOptions();
    public function setWidgetOptions(array $options);
    
    public function isRequired();
    public function setRequired($required);
    
    public function getWeight();
    public function setWeight($weight);
}