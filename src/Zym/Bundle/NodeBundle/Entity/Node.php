<?php
namespace Zym\Bundle\NodeBundle\Entity;

use Zym\Bundle\FieldBundle\FieldCollection;
use Zym\Bundle\FieldBundle\FieldableInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity(repositoryClass="NodeRepository")
 * @ORM\Table(name="nodes")
 */
class Node implements FieldableInterface
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
     * Node Type
     *
     * @var NodeType
     *
     * @ORM\ManyToOne(targetEntity="NodeType", fetch="EAGER")
     * @ORM\JoinColumn(name="node_type", referencedColumnName="type", nullable=false, onDelete="CASCADE")
     */
    protected $nodeType;

    /**
     * Title
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * Field Configs
     *
     * @var array
     */
    protected $fieldConfigs = array();

    /**
     * Fields
     *
     * @var array
     *
     * ORM\OneToMany(targetEntity="Zym\Bundle\FieldBundle\Entity\Field", mappedBy="entityId", cascade={"persist"}, fetch="EAGER")
     * ORM\JoinColumn(name="id", referencedColumnName="entity_id", onDelete="CASCADE")
     */
    protected $fields = array();

    /**
     * Node Type
     *
     * @var FieldCollection
     */
    protected $fieldCollection;

    public function __construct(NodeType $nodeType)
    {
        $this->fieldConfigs = $nodeType->getFieldConfigs();
        $this->nodeType     = $nodeType;
        $this->fields       = new FieldCollection($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNodeType()
    {
        return $this->nodeType;
    }

    public function setNodeType(NodeType $nodeType)
    {
        $this->nodeType = $nodeType;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getFieldedId()
    {
        return $this->id;
    }

    public function getFieldConfigs()
    {
        if (!$this->fieldConfigs) {
            $this->fieldConfigs = $this->nodeType->getFieldConfigs();
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
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }
}