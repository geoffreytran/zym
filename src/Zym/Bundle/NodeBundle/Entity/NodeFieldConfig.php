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

use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Zym\Bundle\FieldBundle\FieldConfigInterface;
use Zym\Bundle\FieldBundle\FieldTypeInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="node_field_configs")
 */
class NodeFieldConfig extends FieldConfig
{
    /**
     * Entity Id
     *
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="NodeType", inversedBy="fieldConfigs")
     * @ORM\JoinColumn(name="node_type", referencedColumnName="type", nullable=false, onDelete="CASCADE")
     */
    protected $nodeType;
    
    /**
     * Get the node type
     *
     * @return NodeType
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }
    
    /**
     * Set the node type
     *
     * @param NodeType $nodeType
     * @return NodeFieldConfig
     */
    public function setNodeType(NodeType $nodeType)
    {
        $this->nodeType = $nodeType;
        return $this;
    }
}