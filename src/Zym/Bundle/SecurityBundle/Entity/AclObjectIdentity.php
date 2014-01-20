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

namespace Zym\Bundle\SecurityBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Acl Security Identity
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 *
 * ORM\Entity()
 * @ORM\Table(
 *     name="acl_object_identities",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"object_identifier", "class_id"})
 *     },
 *     indexes={
 *         @ORM\Index(columns={"parent_object_identity_id"})
 *     }
 * )
 */
class AclObjectIdentity
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
     * Parent Object Identity
     *
     * @var AclObjectIdentity
     *
     * @ORM\Column(name="parent_object_identity_id", type="integer", nullable=true)
     * ORM\ManyToOne(targetEntity="AclObjectIdentityAncestor")
     * ORM\JoinColumn(name="parent_object_identity_id", referencedColumnName="object_identity_id", nullable=true)
     */
    private $aclParentObjectIdentity;

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
     * Identifier
     *
     * @var string
     *
     * @ORM\Column(name="object_identifier", type="string", length=100)
     */
    private $objectIdentifier;

    /**
     * Entries Inheriting
     *
     * @var boolean
     *
     * @ORM\Column(name="entries_inheriting", type="boolean")
     */
    private $entriesInheriting = false;

    /**
     * Ancestors
     *
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="AclObjectIdentity")
     * @ORM\JoinTable(name="acl_object_identity_ancestors",
     *      joinColumns={@ORM\JoinColumn(name="object_identity_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ancestor_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $ancestors;

    /**
     * Get the ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}