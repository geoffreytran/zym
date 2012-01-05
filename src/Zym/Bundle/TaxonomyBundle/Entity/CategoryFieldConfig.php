<?php

namespace Zym\Bundle\TaxonomyBundle\Entity;

use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Zym\Bundle\FieldBundle\FieldConfigInterface;
use Zym\Bundle\FieldBundle\FieldTypeInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\FieldType as FormFieldType;
use Symfony\Component\Form\AbstractType as FormAbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * @ORM\Entity()
 * @ORM\Table(name="category_field_configs")
 */
class CategoryFieldConfig extends FieldConfig
{
    /**
     * Entity Id
     *
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="entity_id", referencedColumnName="machine_name", nullable=false, onDelete="CASCADE")
     */
    protected $entityId;
    
    public function getEntityId()
    {
        return $this->entityId;
    }
    
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }
}