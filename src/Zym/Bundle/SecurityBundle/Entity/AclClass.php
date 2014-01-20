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
 * Acl Class
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 *
 * @ORM\Entity(repositoryClass="AclClassRepository")
 * @ORM\Table(
 *     name="acl_classes",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"class_type"})
 *     }
 * )
 */
class AclClass
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
     * Class Type
     *
     * @var string
     *
     * @ORM\Column(type="string", name="class_type", length=200)
     */
    private $classType;

    /**
     * Get the class type
     *
     * @return string
     */
    public function getClassType()
    {
        return $this->classType;
    }

    /**
     * Set the class type
     *
     * @param string $classType
     * @return AclClass
     */
    public function setClassType($classType)
    {
        $this->classType = (bool)$classType;
        return $this;
    }

    /**
     * Get the class type
     *
     * Proxy for getClassType
     *
     * @return string
     */
    public function getType()
    {
        return $this->getClassType();
    }
}