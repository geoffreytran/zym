<?php
namespace Zym\Bundle\RouterBundle\Entity;

use Symfony\Component\Routing\Route as BaseRoute;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Acl\Model\DomainObjectInterface;

/**
 * Route
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 *
 * @ORM\Entity(repositoryClass="Zym\Bundle\RouterBundle\Entity\RouteRepository")
 * @ORM\Table(
 *     name="routes",
 *     indexes={
 *         @ORM\Index(name="weight_idx", columns={"weight"}),
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Route extends BaseRoute implements DomainObjectInterface
{
    /**
     * Name
     *
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * Methods
     *
     * @ORM\Column(type="json_array")
     */
    private $methods = array();

    /**
     * Schemes
     *
     * @ORM\Column(type="json_array")
     */
    private $schemes = array();

    /**
     * Host
     *
     * @ORM\Column(type="string")
     */
    private $host = '';

    /**
     * Path
     *
     * @ORM\Column(type="string")
     */
    private $path;

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
     * Weight
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $weight = 0;

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
     * @param string       $name         Name of the route
     * @param string       $path         The path pattern to match
     * @param array        $defaults     An array of default parameter values
     * @param array        $requirements An array of requirements for parameters (regexes)
     * @param array        $options      An array of options
     * @param string       $host         The host pattern to match
     * @param string|array $schemes      A required URI scheme or an array of restricted schemes
     * @param string|array $methods      A required HTTP method or an array of restricted methods
     *
     * @api
     */
    public function __construct($name, $path, array $defaults = array(), array $requirements = array(), array $options = array(), $host = '', $schemes = array(), $methods = array())
    {
        $this->setName($name);
        parent::__construct($path, $defaults, $requirements, $options, $host, $schemes, $methods);

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
     * Get the methods
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Sets the HTTP methods (e.g. 'POST') this route is restricted to.
     * So an empty array means that any method is allowed.
     *
     * This method implements a fluent interface.
     *
     * @param string|array $methods The method or an array of methods
     *
     * @return Route The current Route instance
     */
    public function setMethods($methods)
    {
        parent::setMethods($methods);
        $this->methods = parent::getMethods();
        return $this;
    }

    /**
     * Returns the lowercased schemes this route is restricted to.
     * So an empty array means that any scheme is allowed.
     *
     * @return array The schemes
     */
    public function getSchemes()
    {
        return $this->schemes;
    }

    /**
     * Sets the schemes (e.g. 'https') this route is restricted to.
     * So an empty array means that any scheme is allowed.
     *
     * This method implements a fluent interface.
     *
     * @param string|array $schemes The scheme or an array of schemes
     *
     * @return Route The current Route instance
     */
    public function setSchemes($schemes)
    {
        parent::setSchemes($schemes);
        $this->schemes = parent::getSchemes();
        return $this;
    }

    /**
     * Returns the pattern for the host.
     *
     * @return string The host pattern
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the pattern for the host.
     *
     * This method implements a fluent interface.
     *
     * @param string $pattern The host pattern
     *
     * @return Route The current Route instance
     */
    public function setHost($pattern)
    {
        parent::setHost($pattern);
        $this->host = parent::getHost();
        return $this;
    }

    /**
     * Get the path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the path
     *
     * @param string $pattern
     * @return Route
     */
    public function setPath($pattern)
    {
        parent::setPath($pattern);
        $this->path = parent::getPath();

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
     * @return \Zym\Bundle\RouterBundle\Entity\Route
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
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
        parent::setPath($this->path);
        parent::setDefaults($this->defaults);
        parent::setRequirements($this->requirements);
        parent::setOptions($this->options);
        parent::setHost($this->host);
        parent::setMethods($this->methods);
        parent::setSchemes($this->schemes);
    }

    /**
     * Returns a unique identifier for this domain object.
     *
     * @return string
     */
    public function getObjectIdentifier()
    {
        return $this->getName();
    }
}