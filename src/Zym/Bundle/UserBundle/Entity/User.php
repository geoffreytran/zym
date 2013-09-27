<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */

namespace Zym\Bundle\UserBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User Entity
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="users")
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="object_type", type="string")
 * @ORM\DiscriminatorMap({"Zym\Bundle\UserBundle\Entity\User" = "Zym\Bundle\UserBundle\Entity\User"})
 * 
 * @UniqueEntity(fields="username", message="This username is already exists.")
 * @UniqueEntity(fields="email",    message="This email is already exists.")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Zym\Bundle\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $groups;
    
    /**
     * The salt to use for hashing
     *
     * @var string
     * 
     * @Serializer\Exclude()
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * 
     * @Serializer\Exclude()
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     * 
     * @Serializer\Exclude()
     */
    protected $plainPassword;
    
    /**
     * Time Zone
     *
     * @var string
     * 
     * @ORM\Column(name="time_zone", type="string", nullable=true)
     */
    protected $timeZone;
    
    /**
     * Created At
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    
    /**
     * Updated At
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;
    
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->createdAt = new \DateTime();
    }

    /**
     * Sets the email.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        if ($this->username === null) {
            $this->setUsername($email);
        }

        parent::setEmail($email);
    }
    
    /**
     * Get a DateTimeZone instance for the user
     * 
     * @return \DateTimeZone
     */
    public function getDateTimeZone()
    {
        $timeZone = $this->timeZone ?: date_default_timezone_get();
        
        return new \DateTimeZone($timeZone);
    }
    
    /**
     * Get the time zone
     * 
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Set the time zone
     * 
     * @param string $timeZone
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }
    
    /**
     * Get created at
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set created at
     * 
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get updated at
     * 
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updated at
     * 
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}