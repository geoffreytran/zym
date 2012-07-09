<?php
namespace Zym\Bundle\SecurityBundle\Entity;

use Zym\Bundle\SecurityBundle\Http\AccessRuleInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Access Control Rules
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 *
 * @ORM\Entity(repositoryClass="AccessRuleRepository")
 * @ORM\Table(
 *     name="access_rules"
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class AccessRule implements AccessRuleInterface
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
     * Path
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * Roles
     *
     * @var array
     *
     * @ORM\Column(type="json")
     */
    protected $roles = array();

    /**
     * Requires Channel (http/https, etc...)
     *
     * @var string
     *
     * @ORM\Column(type="string", name="channel", nullable=true)
     */
    protected $channel;

    /**
     * Host
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $host;

    /**
     * Methods
     *
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $methods;

    /**
     * IP
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * Attributes
     *
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    protected $attributes = array();

    public function __construct()
    {
        $this->roles = new ArrayCollection(array());
    }

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
     * Get the request matcher
     *
     * @return RequestMatcher
     */
    public function getRequestMatcher()
    {
        $path       = $this->path;
        $host       = $this->host;
        $methods    = $this->methods;
        $ip         = $this->ip;
        $attributes = $this->attributes;

        $requestMatcher = new RequestMatcher($path, $host, $methods, $ip, (array)$attributes);

        return $requestMatcher;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setRoles($roles)
    {
        foreach ($roles as $key => $role) {
            if ($role instanceof AclSecurityIdentity) {
                $roles[$key] = $role->getIdentity();
            }
        }

        $this->roles = $roles;
        return $this;
    }

    /**
     * Get the roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Get the required channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get the host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the methods
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Get the IP
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return (array)$this->attributes;
    }

    /**
     * onPrePersist
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function onPrePersist()
    {
        if ($this->roles instanceof Collection) {
            $this->roles = $this->roles->getValues();
            foreach ($this->roles as $key => $role) {
                if ($role instanceof AclSecurityIdentity) {
                    $this->roles[$key] = $role->getIdentifier();
                }
            }
        }
    }
}