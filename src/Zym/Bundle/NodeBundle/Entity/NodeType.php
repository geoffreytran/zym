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

namespace Zym\Bundle\NodeBundle\Entity;

use Zym\Bundle\FieldBundle\FieldableProxyInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Zym\Bundle\NodeBundle\Entity\NodeTypeRepository")
 * @ORM\Table(name="node_types")
 *
 * @UniqueEntity(fields="type", message="A content type with this machine name exists.")
 */
class NodeType implements DomainObjectInterface, FieldableProxyInterface
{
    /**
     * Type
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", name="type")
     */
    protected $type;

    /**
     * Name
     *
     * @var string
     *
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    /**
     * Field Configs
     *
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Zym\Bundle\NodeBundle\Entity\NodeFieldConfig", mappedBy="nodeType", fetch="EAGER")
     */
    protected $fieldConfigs;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable = true)
     */
    protected $description;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->fieldConfigs = new ArrayCollection();
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type
     *
     * @param string $type
     * @return \Zym\Bundle\NodeBundle\Entity\NodeType
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param string $name
     * @return \Zym\Bundle\NodeBundle\Entity\NodeType
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the description
     *
     * @param string $description
     * @return \Zym\Bundle\NodeBundle\Entity\NodeType
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get the field proxy id
     *
     * @return string
     */
    public function getFieldProxyId()
    {
        return $this->getType();
    }

    /**
     * Get the field configs
     *
     * @return Collection
     */
    public function getFieldConfigs()
    {
        return $this->fieldConfigs;
    }

    /**
     * Set the field configs
     *
     * @param array $fieldConfigs
     * @return \Zym\Bundle\NodeBundle\Entity\NodeType
     */
    public function setFieldConfigs(array $fieldConfigs)
    {
        $this->fieldConfigs = $fieldConfigs;
        return $this;
    }

    /**
     * Returns a unique identifier for this domain object.
     *
     * @return string
     */
    public function getObjectIdentifier()
    {
        return $this->type;
    }

    /**
     * To String
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->type;
    }
}