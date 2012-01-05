<?php

namespace Zym\Bundle\FieldBundle\Entity;

use Zym\Bundle\FieldBundle\FieldConfigInterface;
use Zym\Bundle\FieldBundle\FieldTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="field_types")
 */
class FieldType implements FieldTypeInterface
{
    /**
     * Machine Name
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", name="machine_name")
     */
    protected $machineName;
    
    /**
     * Field Type
     *
     * @var string
     * 
     * @ORM\Column(type="string", name="type")
     */
    protected $type;
    
    /**
     * Value Count
     *
     * @var integer
     *
     * @ORM\Column(type="integer", name="value_count")
     */
    protected $valueCount;
    
    public function getMachineName()
    {
        return $this->machineName;
    }
    
    public function setMachineName($machineName)
    {
        return $this->machineName = $machineName;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    public function getValueCount()
    {
        return $this->valueCount;
    }
        
    public function setValueCount($valueCount)
    {
        $this->valueCount = $valueCount;
        return $this;
    }
    
    public function __toString()
    {
        return $this->getMachineName();
    }
}