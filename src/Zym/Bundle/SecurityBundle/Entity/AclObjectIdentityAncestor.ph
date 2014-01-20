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
 * Acl Object Identity Ancestors
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="acl_object_identity_ancestors",
 *     indexes={
 *         @ORM\Index(columns={"object_identity_id"}),
 *         @ORM\Index(columns={"ancestor_id"})
 *     }
 * )
 */
class AclObjectIdentityAncestor
{
    /**
     * ID
     *
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AclObjectIdentity")
     * @ORM\JoinColumn(name="object_identity_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $objectIdentity;

    /**
     * ID
     *
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AclObjectIdentity")
     * @ORM\JoinColumn(name="ancestor_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $ancestor;
}