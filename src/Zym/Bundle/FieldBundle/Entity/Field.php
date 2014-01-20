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

namespace Zym\Bundle\FieldBundle\Entity;

use Zym\Bundle\FieldBundle\FieldInterface;
use Zym\Bundle\FieldBundle\FieldConfigInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="FieldRepository")
 * @ORM\Table(name="fields")
 * @ORM\HasLifecycleCallbacks 
 */
abstract class Field implements FieldInterface//, \ArrayAccess, \Iterator
{
    /**
     * ID
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Entity
     *
     * @var object
     */
    protected $entity;
    
    /**
     * Entity Type
     * 
     * 
     * @ORM\Column(type="string", name="entity_type")
     */
    protected $entityType;
    
    /**
     * Entity Id
     *
     * @var integer
     *
     * 
     * ORM\ManyToOne(targetEntity="\ZymNodeBundle:Node")
     * ORM\JoinColumn(name="entity_id", referencedColumnName="id")
     * @ORM\Column(type="integer", name="entity_id")
     */
    protected $entityId;
        
    /**
     * Field Config
     *
     * @var FieldConfig
     *
     * @ORM\ManyToOne(targetEntity="FieldConfig", fetch="EAGER")
     * @ORM\JoinColumn(name="field_config", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $fieldConfig;
        
    public function getId()
    {
        return $this->id;
    }
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    public function getEntityType()
    {
        return $this->entityType;
    }
    
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
        return $this;
    }
    
    public function getEntityId()
    {
        return $this->entityId;
    }
    
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }
    
    public function getFieldConfig()
    {
        return $this->fieldConfig;
    }
    
    public function setFieldConfig(FieldConfigInterface $fieldConfig)
    {
        $this->fieldConfig = $fieldConfig;
        return $this;
    }
    
    /**
     * @ORM\PrePersist 
     */
    public function onPrePersist()
    {
        
        if ($this->entityType === null && $this->entity) {
            $this->entityType = get_class($this->entity);
        }
        
        if ($this->entityId === null && $this->entity) {
            $this->entityId = $this->entity->getFieldedId();
        }
    }
}