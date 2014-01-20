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
 * @ORM\Entity(repositoryClass="AclSecurityIdentityRepository")
 * @ORM\Table(
 *     name="acl_security_identities",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"identifier", "username"})
 *     }
 * )
 */
class AclSecurityIdentity
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
     * Identifier
     *
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $identifier;

    /**
     * Username
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $username = false;

    /**
     * Get the ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set the identifier
     *
     * @param string $identifier
     * @return AclSecurityIdentity
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Get the username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the username
     *
     * @param string $username
     * @return AclSecurityIdentity
     */
    public function setUsername($username)
    {
        $this->username = (bool)$username;
        return $this;
    }
}