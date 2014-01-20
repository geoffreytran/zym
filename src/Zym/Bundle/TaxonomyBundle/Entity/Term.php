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
 * Zym\Bundle\TaxonomyBundle\Entity\Term
 *
 * @ORM\Table(name="terms")
 * @ORM\Entity(repositoryClass="TermRepository")
 */
class Term implements FieldableInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var integer $weight
     *
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @var Category $category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="terms")
     * @ORM\JoinColumn(name="category", referencedColumnName="machine_name", nullable=false)
     */
    protected $category;

    protected $fieldConfigs = array();

    /**
     * Fields
     *
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Zym\Bundle\FieldBundle\Entity\Field", mappedBy="entityId", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="id", referencedColumnName="entity_id", onDelete="CASCADE")
     */
    protected $fields = array();
    protected $fieldCollection;

    public function __construct(Category $category)
    {
        $this->fieldConfigs = $category->getTermFieldConfigs();
        $this->category     = $category;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Term
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
     * @param text $description
     * @return Term
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Term
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getFieldedId()
    {
        return $this->id;
    }

    public function getFieldConfigs()
    {
        if (!$this->fieldConfigs) {
            $this->fieldConfigs = $this->category->getTermFieldConfigs();
        }

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