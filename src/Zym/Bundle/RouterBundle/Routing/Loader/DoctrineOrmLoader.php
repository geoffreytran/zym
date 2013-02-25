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

namespace Zym\Bundle\RouterBundle\Routing\Loader;

use Zym\Bundle\RouterBundle\Routing\Resource\DoctrineOrmResource;
use Doctrine\ORM;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

/**
 * Routing Doctrine ORM Loader
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class DoctrineOrmLoader extends BaseLoader
{
    /**
     * EntityManager
     *
     * @var ORM\EntityManager
     */
    private $entityManager;

    /**
     * Construct
     *
     * @param ORM\EntityManager $entityManager
     */
    public function __construct(ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Loads a resource.
     *
     * @param mixed  $resource The resource
     * @param string $type     The resource type
     */
    public function load($resource, $type = null)
    {
        $entityManager = $this->getEntityManager();

        $collection = new RouteCollection();
        $collection->addResource(new DoctrineOrmResource('ZymRouterBundle:Route'));

        try {
            $routes = $entityManager->getRepository('ZymRouterBundle:Route')
                                    ->findAll();
        } catch (\Doctrine\DBAL\DBALException $e) {
            $routes = array();
        }

        foreach ($routes as $route) {
            $collection->add($route->getName(), $route);
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return Boolean True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return ('orm' === $type);
    }

    /**
     * Get the entity manager
     *
     * @return ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Set the entity manager
     *
     * @param ORM\EntityManager $entityManager
     * @return DoctrineOrmLoader
     */
    public function setEntityManager(ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }
}