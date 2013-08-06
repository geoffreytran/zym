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

namespace Zym\Bundle\MenuBundle\Entity\MenuItem;

use Zym\Bundle\MenuBundle\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Section Menu Item
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Entity(repositoryClass="Zym\Bundle\MenuBundle\Entity\MenuItemRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class SectionMenuItem extends Entity\MenuItem
{
    /**
     * URI
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $uri;

    /**
     * Route Name
     *
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotNull(message = "Invalid Route.")
     */
    private $route;

    /**
     * Route Params
     *
     * @var array
     *
     * @ORM\Column(type="json_array", name="route_parameters")
     */
    private $routeParameters = array();

    /**
     * Get the route of the link
     *
     * If this method returns null, getUri() will be used.
     * If both a route and an uri are provided, the route is used.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set the route name
     *
     * @param string $routeName
     * @return RoutedMenuItem
     */
    public function setRoute($name)
    {
        $this->route = $name;
        return $this;
    }

    /**
     * Get the route params
     *
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * Set the route params
     *
     * @param array $routeParams
     * @return RoutedMenuItem
     */
    public function setRouteParameters(array $routeParams)
    {
        $this->routeParameters = $routeParams;
        return $this;
    }

    /**
     * Whether the generated uri should be absolute
     *
     * @return boolean
     */
    public function isRouteAbsolute()
    {
        return $this->isAbsolute();
    }

    /**
     * Get the uri of the link
     *
     * @return string
     *
     * @Assert\NotNull(message = "URI does not match any routes.")
     */
    public function getUri()
    {
        if ($this->route === null) {
            return null;
        }

        $router = $this->getRouter();

        if ($router) {
            return $router->generate($this->getRoute(), $this->getRouteParameters());
        }

        return $this->uri;
    }
}