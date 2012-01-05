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

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use FOS\UserBundle\Entity\User as BaseUser;
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
 * @ORM\DiscriminatorMap({"Zym\Bundle\UserBundle\Entity\User" = "User"})
 *
 * @UniqueEntity(fields="username", message="This email is already used.")
 * @UniqueEntity(fields="email",    message="This email is already used.")
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
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * First Name
     *
     * @var string
     *
     * @Assert\MaxLength(
     *     limit = 255,
     *     message = "First name is too long. It should have {{ limit }} characters or less."
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", name="first_name", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * Last Name
     *
     * @var string
     *
     * @Assert\MaxLength(
     *     limit = 255,
     *     message = "Last name is too long. It should have {{ limit }} characters or less."
     * )
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", name="last_name", length=255, nullable=true)
     */
    protected $lastName;

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
     * Get the first name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the first name
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = ucfirst($firstName);
        return $this;
    }

    /**
     * Get the last name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the last name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = ucfirst($lastName);
        return $this;
    }

    /**
     * Get the full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}