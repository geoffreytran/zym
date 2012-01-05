<?php

namespace Zym\Bundle\FieldBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="field_data_entities")
 */
class EntityField extends Field
{    
    
    protected $entityId;
    /**
     * Entity
     *
     * @var string
     */
    protected $entity;
    
    public function getEntity()
    {
        return $this->entity;
    }
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    public function getData()
    {
        return $this->getEntity();
    }
    
    public function setData($data)
    {
        return $this->setEntity($data);
    }
    
    public function __isset($name)
    {
        return isset($this->entity->$name);
    }
    
    public function __get($name)
    {
        return $this->entity->$name;
    }
    
    public function __set($name, $value)
    {
        $this->entity->$name = $value;
    }
    
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->entity, $name), $arguments);
    }
}