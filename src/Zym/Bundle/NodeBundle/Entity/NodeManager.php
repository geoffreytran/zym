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

namespace Zym\Bundle\NodeBundle\Entity;

use Zym\Bundle\FieldBundle\Entity\FieldManager;
use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Node Manager
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class NodeManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var NodeRepository
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
                                SecurityContextInterface $securityContext,
                                FieldManager $fieldManager = null)
    {
        parent::__construct($entityManager, $class, $paginator, $aclProvider, $securityContext);
        $this->fieldManager = $fieldManager;
    }

    /*
     * Create a node
     *
     * @param Node $node
     * @return Node
     */
    public function createNode(Node $node)
    {
        //var_dump($node);exit;
        parent::createEntity($node);

        return $node;
    }

    /**
     * Save a node
     *
     * @param Node $node
     * @param boolean $andFlush
     */
    public function saveNode(Node $node, $andFlush = true)
    {
        parent::saveEntity($node, $andFlush);

        if ($this->fieldManager !== null) {
            $this->fieldManager->saveFields($node->getFields(), $andFlush);
        }
    }

    /**
     * Delete a node
     *
     * @param Node $node
     */
    public function deleteNode(Node $node)
    {
        parent::deleteEntity($node);
    }

    /**
     * Find nodes
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findNodes(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        $entities =  $this->repository->findNodes($criteria, $page, $limit, $orderBy);
        $this->loadAcls($entities);
        
        return $entities;
    }

    /**
     * Find a node by criteria
     *
     * @param array $criteria
     * @return Node
     */
    public function findNodeBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}