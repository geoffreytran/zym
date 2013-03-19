<?php

namespace Zym\Bundle\ThemeBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Theme Rule
 *
 * @ORM\Entity(repositoryClass="ThemeRuleRepository")
 * @ORM\Table(name="theme_rules")
 */
class ThemeRule
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
     * Path
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * Theme
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $theme;

    /**
     * Requires Channel (http/https, etc...)
     *
     * @var string
     *
     * @ORM\Column(type="string", name="channel", nullable=true)
     */
    protected $channel;

    /**
     * Host
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $host;

    /**
     * Methods
     *
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    protected $methods;

    /**
     * IP
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * Attributes
     *
     * @var array
     *
     * @ORM\Column(type="array", nullable=false)
     */
    protected $attributes = array();

    /**
     * Construct
     *
     */
    public function __construct()
    {
    }

    /**
     * Get the ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the request matcher
     *
     * @return RequestMatcher
     */
    public function getRequestMatcher()
    {
        $path       = $this->path;
        $host       = $this->host;
        $methods    = $this->methods;
        $ip         = $this->ip;
        $attributes = $this->attributes;

        $requestMatcher = new RequestMatcher($path, $host, $methods, $ip, (array)$attributes);

        return $requestMatcher;
    }

    public function setPath($path)
    {
        $this->path = $path;
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
     * Set the theme
     *
     * @param string $theme
     * @return \Zym\Bundle\ThemeBundle\Entity\ThemeRule
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Get the theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set the channel
     *
     * @param string $channel
     * @return \Zym\Bundle\ThemeBundle\Entity\ThemeRule
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * Get the required channel
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set the host
     *
     * @param string $host
     * @return \Zym\Bundle\ThemeBundle\Entity\ThemeRule
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get the host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
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
     * Get the IP
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return (array)$this->attributes;
    }
}