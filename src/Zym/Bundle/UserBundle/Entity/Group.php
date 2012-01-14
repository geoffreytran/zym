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

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    FOS\UserBundle\Entity\Group as BaseGroup,
    Doctrine\ORM\Mapping as ORM;

/**
 * User Group Entity
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
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