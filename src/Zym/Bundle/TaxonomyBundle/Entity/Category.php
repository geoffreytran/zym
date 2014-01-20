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

use Zym\Bundle\FieldBundle\FieldableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Zym\Bundle\TaxonomyBundle\Entity\Category
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="CategoryRepository")
 */
class Category implements FieldableInterface
{
    /**
     * @var string $machineName
     *
     * @ORM\Id
     * @ORM\Column(name="machine_name", type="string", length=255)
     */
    private $machineName;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    
    /**
     * Taxonomy Terms
     *
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Term", mappedBy="category")
     */
    protected $terms = array();

    /**
     * Taxonomy Term Field Configs
     *
     * @var array
     *
     * @ORM\OneToMany(targetEntity="TermFieldConfig", mappedBy="entityId")
     */
    protected $termFieldConfigs = array();
    
    /**
     * Category Field Configs
     *
     * @var array
     *
     * @ORM\OneToMany(targetEntity="CategoryFieldConfig", mappedBy="entityId")
     */
    protected $fieldConfigs = array();
    
    /**
     * Fields
     *
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Zym\Bundle\FieldBundle\Entity\Field", mappedBy="entityId", cascade={"persist"}, fetch="EAGER")
     */
    protected $fields = array();
    
    protected $fieldCollection;
    
    /**
     * Set machineName
     *
     * @param string $machineName
     * @return Category
     */
    public function setMachineName($machineName)
    {
        $this->machineName = $machineName;
        return $this;
    }

    /**
     * Get machineName
     *
     * @return string 
     */
    public function getMachineName()
    {
        return $this->machineName;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setTerms(array $terms)
    {
        $this->terms = $terms;
        return $this;
    }
    
    public function getTerms()
    {
        return $this->terms;
    }
    
    public function getTermFieldConfigs()
    {
        return $this->termFieldConfigs;
    }
    
    public function setTermFieldConfigs(array $termFieldConfigs)
    {
        $this->termFieldConfigs = $termFieldConfigs;
        return $this;
    }
    
    public function getFieldedId()
    {
        return $this->machineName;
    }
    
    public function getFieldConfigs()
    {
        return $this->fieldConfigs;
    }
    
    public function setFieldConfigs(array $fieldConfigs)
    {
        $this->fieldConfigs = $fieldConfigs;
        return $this;
    }
    
    public function getFields()
    {
        if ($this->fieldCollection === null) {
            $fieldCollection       = new FieldCollection($this->fields, $this->getFieldConfigs());
            $this->fieldCollection = $fieldCollection;
        }
        
        return $this->fieldCollection;
    }
    
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }
}