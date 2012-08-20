<?php
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
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
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