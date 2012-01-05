<?php
namespace Zym\Bundle\NodeBundle\Entity;

use Zym\Bundle\FieldBundle\FieldableInterface;
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
class NodeType implements DomainObjectInterface
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
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getFieldConfigs()
    {
        return $this->fieldConfigs;
    }
    
    public function setFieldConfigs($fieldConfigs)
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