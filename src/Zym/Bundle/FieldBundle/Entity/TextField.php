<?php

namespace Zym\Bundle\FieldBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="field_data_texts")
 */
class TextField extends Field
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
    
    public function __toString()
    {
        return $this->getValue();
    }
}