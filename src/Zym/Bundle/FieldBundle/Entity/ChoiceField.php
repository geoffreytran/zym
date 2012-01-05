<?php

namespace Zym\Bundle\FieldBundle\Entity;

class ChoiceField extends Field
{
    /**
     * Value
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $value;
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function getData()
    {
        return $this->getValue();
    }
    
    public function setData($data)
    {
        return $this->setValue($data);
    }
}