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

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;


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
     * Construct
     *
     * @param string $name
     * @param array $roles
     */
    public function __construct($name = null, $roles = array())
    {
        $this->name = $name;
        $this->roles = $roles;
    }
}