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

use Knp\Menu\MenuItem as BaseMenuItem;
use Knp\Menu\FactoryInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Menu Item
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Entity(repositoryClass="MenuItemRepository")
 * @ORM\Table(name="menu_items")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="object_type", type="string")
 * @ORM\DiscriminatorMap({
 *     "Zym\Bundle\MenuBundle\Entity\MenuItem\StaticMenuItem" = "Zym\Bundle\MenuBundle\Entity\MenuItem\StaticMenuItem",
 *     "Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem" = "Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem"
 * })
 */
abstract class MenuItem extends BaseMenuItem
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
     * Menu
     *
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", cascade={"all"})
     * @ORM\JoinColumn(name="menu", referencedColumnName="name", nullable=false)
     */
    protected $menu;

    /**
     * Name
     *
     * @var string
     *
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
     * Label attributes
     *
     * @var array
     *
     * @ORM\Column(type="json_array", name="label_attributes")
     */
    protected $labelAttributes = array();

    /**
     * Description
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * Attributes
     *
     * @var string
     *
     * @ORM\Column(type="json_array")
     */
    protected $attributes = array();
    
    /**
     * Display
     *
     * Whether to render this menu
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    protected $display = true;
    
    /**
     * Display Children
     *
     * Whether to show this menu's children
     *
     * @var string
     *
     * @ORM\Column(type="boolean", name="display_children")
     */
    protected $displayChildren = true;

    /**
     * Options
     *
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    protected $options = array();


    /**
     * Weight
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $weight = 0;

    /**
     * Left
     *
     * @var integer
     *
     * @Gedmo\TreeLeft()
     *
     * @ORM\Column(name="lft", type="integer")
     */
    protected $lft;

    /**
     * Level
     *
     * @var integer
     *
     * @Gedmo\TreeLevel()
     *
     * @ORM\Column(name="lvl", type="integer")
     */
    protected $lvl;

    /**
     * Right
     *
     * @var integer
     *
     * @Gedmo\TreeRight()
     * @ORM\Column(name="rgt", type="integer")
     */
    protected $rgt;

    /**
     * Root
     *
     * @Gedmo\TreeRoot()
     * @ORM\Column(name="root", type="integer")
     */
    protected $root;

    /**
     * Menu Item
     *
     * @var MenuItem
     *
     * @Gedmo\TreeParent()
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children", cascade={"all"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $parent;

    /**
     * Children
     *
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent", cascade={"all"})
     * @ORM\OrderBy({ "weight" = "ASC", "lft" = "ASC"})
     */
    protected $children;
    
    /**
     * Router
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Class constructor
     *
     * @param string $name      The name of this menu, which is how its parent will
     *                          reference it. Also used as label if label not specified
     * @param \Knp\Menu\FactoryInterface $factory
     *
     * @param Menu $menu
     */
    public function __construct($name, FactoryInterface $factory = null, Menu $menu = null)
    {
        parent::__construct($name, $factory);
        
        $this->menu = $menu;
        $this->children = new ArrayCollection();
    }

    /**
     * Get the id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * @return MenuItem
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
     * @return MenuItem
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get the menu
     *
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set the menu
     *
     * @param Menu $menu
     * @return MenuItem
     */
    public function setMenu(Menu $menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * Get the weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the weight
     *
     * @param integer $weight
     * @return MenuItem
     */
    public function setWeight($weight)
    {
        $this->weight = (int)$weight;
        return $this;
    }

    /**
     * Get the root menu item
     *
     * @return MenuItem
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set the root menu item
     *
     * @param MenuItem $root
     * @return MenuItem
     */
    public function setRoot(MenuItem $root)
    {
        $this->root = $root;
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
        $child = parent::addChild($child, $options);
        $child->setMenu($this->menu);
        
        return $child;
    }
    
    /**
     * Set the router
     *
     * @param RouterInterface $router
     * @return Menu
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
        return $this;
    }
    
    /**
     * Get the router
     *
     * @return RouterInterface
     */
    public function getRouter()
    {
        if ($this->router === null) {
            if ($this->getParent() && ($router = $this->getParent()->getRouter())) {
                /**
                 * This should look strange. But, if we ask our parent for the
                 * current uri, and it returns it successfully, then one of two
                 * different things just happened:
                 *
                 *   1) The parent already had the router calculated, but it
                 *      hadn't been passed down to the child yet. This technically
                 *      should not happen, but we allow for the possibility. In
                 *      that case, router is still blank and we set it here.
                 *   2) The parent did not have the router calculated, and upon
                 *      calculating it, it set it on itself and all of its children.
                 *      In that case, this menu item and all of its children will
                 *      now have the router just by asking the parent.
                 */
                if ($this->router === null) {
                    $this->setRouter($router);
                }
            }
        }
        
        return $this->router;
    }
    
    /**
     * Get the children
     *
     * @return array
     */
    public function getChildren()
    {
        if ($this->children instanceof \Doctrine\Common\Collections\Collection) {
            return $this->children->toArray();
        }
        
        return parent::getChildren();
    }
}