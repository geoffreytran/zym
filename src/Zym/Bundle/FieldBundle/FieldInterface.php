<?php

namespace Zym\Bundle\FieldBundle;

interface FieldInterface
{
    public function getFieldConfig();
    public function setFieldConfig(FieldConfigInterface $fieldConfig);
    
    public function getData();
    public function setData($data);
}