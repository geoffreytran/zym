<?php

/**
 * Zym Framework
 *
 * This file is part of the Zym package.
 *
 * @link      https://github.com/geoffreytran/zym for the canonical source repository
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3 License
 */

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