<?php
namespace Zym\Bundle\RouterBundle\Entity;

use Symfony\Component\Routing\Route as BaseRoute;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Route
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 *
 * @ORM\Entity(repositoryClass="ZymRouterBundle:RouteRepository")
 * @ORM\Table(name="routes")
 * @ORM\HasLifecycleCallbacks()
 */
class Route extends BaseRoute
{
    /**
     * Name
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Pattern
     *
     * @ORM\Column(type="string")
     */
    private $pattern;

    /**
     * Defaults
     *
     * @ORM\Column(type="json_array")
     */
    private $defaults = array();

    /**
     * Requirements
     *
     * @ORM\Column(type="json_array")
     */
    private $requirements = array();

    /**
     * Options
     *
     * @ORM\Column(type="json_array")
     */
    private $options = array();

    /**
     * UpdatedAt
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;

    /**
     * Compilers
     *
     * @var array
     */
    static protected $compilers = array();

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * compiler_class: A class name able to compile this route instance (RouteCompiler by default)
     *
     * @param string $name          Route Name
     * @param string $pattern       The pattern to match
     * @param array  $defaults      An array of default parameter values
     * @param array  $requirements  An array of requirements for parameters (regexes)
     * @param array  $options       An array of options
     */
    public function __construct($name, $pattern, array $defaults = array(), array $requirements = array(), array $options = array())
    {
        $this->setName($name);
        $this->setPattern($pattern);
        $this->setDefaults($defaults);
        $this->setRequirements($requirements);
        $this->setOptions($options);

        $this->updatedAt = new \DateTime();
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
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the pattern
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set the pattern
     *
     * @param string $pattern
     * @return Route
     */
    public function setPattern($pattern)
    {
        parent::setPattern($pattern);
        $this->pattern = parent::getPattern();

        return $this;
    }

    /**
     * Get the defaults
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Set the defaults
     *
     * @param array $defaults
     * @return Route
     */
    public function setDefaults(array $defaults)
    {
        parent::setDefaults($defaults);
        $this->defaults = parent::getDefaults();
        return $this;
    }

    /**
     * Get the requirements
     *
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * Set the requirements
     *
     * @param array $requirements
     * @return Route
     */
    public function setRequirements(array $requirements)
    {
        parent::setRequirements($requirements);
        $this->requirements = parent::getRequirements();
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
     * @return Route
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
        $this->options = parent::getOptions();
        return $this;
    }

    /**
     * Get the updated at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the updated at time
     *
     * @param \DateTime $updatedAt
     * @return Route
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Refresh the entity
     *
     * @ORM\PostLoad
     */
    public function refreshEntity()
    {
        parent::setPattern($this->pattern);
        parent::setDefaults($this->defaults);
        parent::setRequirements($this->requirements);
        parent::setOptions($this->options);
    }
}