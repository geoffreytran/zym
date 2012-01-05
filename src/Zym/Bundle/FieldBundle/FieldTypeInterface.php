<?php

namespace Zym\Bundle\FieldBundle;

interface FieldTypeInterface 
{
    public function getMachineName();
    public function setMachineName($machineName);
    
    public function getValueCount();
    public function setValueCount($valueCount);
}