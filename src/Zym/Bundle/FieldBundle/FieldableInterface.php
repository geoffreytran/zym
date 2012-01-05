<?php

namespace Zym\Bundle\FieldBundle;

interface FieldableInterface 
{
    public function getFieldedId();
    
    public function getFieldConfigs();
    public function setFieldConfigs(array $fieldConfigs);
    
    public function getFields();
    public function setFields($fields);
}