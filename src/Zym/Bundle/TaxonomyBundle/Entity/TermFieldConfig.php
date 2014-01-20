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
 * @ORM\Table(name="term_field_configs")
 */
class TermFieldConfig extends FieldConfig
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