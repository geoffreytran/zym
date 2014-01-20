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
 * Routed Menu Item
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 *
 * @Gedmo\Tree(type="nested")
 *
 * @ORM\Entity(repositoryClass="Zym\Bundle\MenuBundle\Entity\MenuItemRepository")
 */
class RoutedMenuItem extends Entity\MenuItem
{
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
     * Set the uri for a menu item
     *
     * @param  string $uri The uri to set on this menu item
     * @return \Knp\Menu\ItemInterface
     */
    public function setUri($uri)
    {
        $this->route = null;
        $this->routeParameters = array();

        $router  = $this->getRouter();
        $context = $router->getContext();

        try {
            $baseUrl = $context->getBaseUrl();
            if (substr($uri, 0, strlen($baseUrl)) == $baseUrl) {
                $uri = substr($uri, strlen($baseUrl));
            }

            $params = $router->match($uri);
            if ($params) {
                $this->setRoute($params['_route']);

                unset($params['_route']);
                $this->setRouteParameters($params);
            }
        } catch (ResourceNotFoundException $e) {
            // Invalid URI
            throw $e;
        } catch (MethodNotAllowedException $e) {
            // Invalid Method, we can only support GET at the moment
            throw $e;
        }

        return $this;
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
        return $router->generate($this->getRoute(), $this->getRouteParameters());
    }
}