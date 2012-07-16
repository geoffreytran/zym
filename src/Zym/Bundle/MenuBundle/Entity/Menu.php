<?php
/**
 * RAPP
 *
 * LICENSE
 *
 * This file is intellectual property of RAPP and may not
 * be used without permission.
 *
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */

namespace Zym\Bundle\MenuBundle\Entity;

use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem as BaseMenuItem;

use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Menu
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 *
 * @ORM\Entity(repositoryClass="MenuRepository")
 * @ORM\Table(name="menus")
 *
 * @UniqueEntity(fields="name", message="This machine name is already used.")
 */
class Menu extends BaseMenuItem implements DomainObjectInterface
{
    /**
     * Name
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Label
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $label;

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * Options
     *
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $options = array();
    
    /**
     * Attributes
     *
     * @var array
     *
     * @ORM\Column(type="json_array");
     */
    protected $attributes = array();

    /**
     * Menu Items
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="menu", cascade={"all"})
     */
    protected $children;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param string $name
     * @return Menu
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the description
     *
     * @param string $description
     * @return Menu
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Get the options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Set the options
     *
     * @param array $options
     * @return Menu
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * Add a child menu item to this menu
     *
     * Returns the child item
     *
     * @param mixed $child   An ItemInterface instance or the name of a new item to create
     * @param array $options If creating a new item, the options passed to the factory for the item
     * @return \Knp\Menu\ItemInterface
     */
    public function addChild($child, array $options = array())
    {
        if (!$child instanceof ItemInterface) {
            throw new \InvalidArgumentException('Child must implement Knp\Bundle\MenuBundle\ItemIterface');
        } elseif (null !== $child->getParent()) {
            throw new \InvalidArgumentException('Cannot add menu item as child, it already belongs to another menu (e.g. has a parent).');
        }

        $child->setMenu($this);

        $this->children[$child->getName()] = $child;
        
        return $child;
    }
    
    /**
     * Get the iterator
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        if (is_array($this->children)) {
            return new \ArrayIterator($this->children);
        }
        
        return $this->children;
    }
    
    /**
     * Returns a unique identifier for this domain object.
     *
     * @return string
     */
    public function getObjectIdentifier()
    {
        return $this->name;
    }
}