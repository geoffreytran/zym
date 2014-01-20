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

namespace Zym\Bundle\RuntimeConfigBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Parameter Manager
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ParameterManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var ParameterRepository
     */
    protected $repository;

    /**
     * Field Manager
     *
     * @var FieldManager
     */
    protected $fieldManager;

    /**
     * Construct
     *
     * @param ORM\EntityManager $entityManager
     * @param string $class
     * @param Paginator $paginator
     * @param MutableAclInterface $aclProvider
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(EntityManager $entityManager, $class,
                                Paginator $paginator,
                                MutableAclProviderInterface $aclProvider,
                                SecurityContextInterface $securityContext)
    {
        parent::__construct($entityManager, $class, $paginator, $aclProvider, $securityContext);
    }

    /*
     * Create a parameter
     *
     * @param Parameter $parameter
     * @return Parameter
     */
    public function createParameter(Parameter $parameter)
    {
        parent::createEntity($parameter);

        return $parameter;
    }

    /**
     * Save a parameter
     *
     * @param Parameter $parameter
     * @param boolean $andFlush
     */
    public function saveParameter(Parameter $parameter, $andFlush = true)
    {
        parent::saveEntity($parameter, $andFlush);
    }

    /**
     * Delete a parameter
     *
     * @param Parameter $parameter
     */
    public function deleteParameter(Parameter $parameter)
    {
        parent::deleteEntity($parameter);
    }

    /**
     * Find parameters
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findParameters(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findParameters($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a parameter by criteria
     *
     * @param array $criteria
     * @return Parameter
     */
    public function findParameterBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find parameter
     *
     * @param string $name
     * @return Parameter
     */
    public function findParameter($name)
    {
        return $this->repository->findOneBy(array('name' => $name));
    }
}