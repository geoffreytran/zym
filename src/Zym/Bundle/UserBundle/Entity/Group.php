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

namespace Zym\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Group
 *
 * User group entity.
 *
 * @package Zym\Bundle\UserBundle\Entity
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 *
 * @ORM\Entity(repositoryClass="GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Zym\Bundle\UserBundle\Entity\User", cascade={"all"}, mappedBy="groups")
     * @ORM\JoinTable(name="user_groups",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $users;

    /**
     * Construct
     *
     * @param string $name
     * @param array $roles
     */
    public function __construct($name = null, $roles = array())
    {
        $this->name = $name;
        $this->roles = $roles;

        $this->users = new ArrayCollection();
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param UserInterface $user
     */
    public function addUser(UserInterface $user)
    {
        $this->users->add($user);
    }

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user)
    {
        $this->users->removeElement($user);
    }
}