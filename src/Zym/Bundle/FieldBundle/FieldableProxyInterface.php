<?php

namespace Zym\Bundle\FieldBundle;

interface FieldableProxyInterface 
{
    public function getFieldProxyId();

    public function getFieldConfigs();
    public function setFieldConfigs(array $fieldConfigs);
}