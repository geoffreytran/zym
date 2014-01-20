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

interface FieldableInterface 
{
    public function getFieldedId();
    
    public function getFieldConfigs();
    public function setFieldConfigs(array $fieldConfigs);
    
    public function getFields();
    public function setFields($fields);
}