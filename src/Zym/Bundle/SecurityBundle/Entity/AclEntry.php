<?php
namespace Zym\Bundle\SecurityBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Acl Class
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 *
 * ORM\Entity(repositoryClass="AclClassRepository")
 * @ORM\Table(
 *     name="acl_entries",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"class_id", "object_identity_id", "field_name", "ace_order"})
 *     },
 *     indexes={
 *         @ORM\Index(columns={"class_id","object_identity_id", "security_identity_id"}),
 *         @ORM\Index(columns={"class_id"}),
 *         @ORM\Index(columns={"object_identity_id"}),
 *         @ORM\Index(columns={"security_identity_id"})
 *     }
 * )
 */
class AclEntry
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
    private $id;

    /**
     * Class
     *
     * @var AclClass
     *
     * @ORM\ManyToOne(targetEntity="AclClass")
     * @ORM\JoinColumn(name="class_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $aclClass;
    
    /**
     * Object Identity
     *
     * @var AclObjectIdentity
     *
     * @ORM\ManyToOne(targetEntity="AclObjectIdentity")
     * @ORM\JoinColumn(name="object_identity_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $aclObjectIdentity;
    
    /**
     * AclSecurityIdentity
     *
     * @var AclSecurityIdentity
     *
     * @ORM\ManyToOne(targetEntity="AclSecurityIdentity")
     * @ORM\JoinColumn(name="security_identity_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $aclSecurityIdentity;
    
    /**
     * Field Name
     *
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=50, nullable=true)
     */
    private $fieldName;
    
    /**
     * Ace Order
     *
     * @var int
     *
     * @ORM\Column(name="ace_order", type="smallint")
     */
    private $aceOrder = 0;
    
    /**
     * Mask
     *
     * @var int
     *
     * @ORM\Column(name="mask", type="integer")
     */
    private $mask = 0;
    
    /**
     * Granting
     *
     * @var string
     *
     * @ORM\Column(name="granting", type="boolean")
     */
    private $granting = true;
    
    /**
     * Granting Strategy
     *
     * @var string
     *
     * @ORM\Column(name="granting_strategy", type="string", length=30)
     */
    private $grantingStrategy = 'all';
    
    /**
     * Audit Success
     *
     * @var boolean
     *
     * @ORM\Column(name="audit_success", type="boolean")
     */
    private $auditSuccess = false;
    
    /**
     * Audit Failure
     *
     * @var boolean
     *
     * @ORM\Column(name="audit_failure", type="boolean")
     */
    private $auditFailure = false;
    
    public function setSecurityIdentity($identity)
    {
        $this->aclSecurityIdentity = $identity;
        return $this;
    }
    
    public function getSecurityIdentity()
    {
        return $this->aclSecurityIdentity;
    }
    
    public function setMask($mask)
    {
        $this->mask = $mask;
        return $this;
    }
    
    public function getMask()
    {
        return $this->mask;
    }
    
    public function setGranting($granting)
    {
        $this->granting = $granting;
        return $this;
    }
    
    public function isGranting()
    {
        return $this->granting;
    }
    
    public function setStrategy($strategy)
    {
        $this->grantingStrategy = $strategy;
        return $this;
    }
    
    public function getStrategy()
    {
        return $this->grantingStrategy;
    }
    
    public function isAuditFailure()
    {
        return $this->auditFailure;
    }
    
    public function isAuditSuccess()
    {
        return $this->auditSuccess;
    }
}