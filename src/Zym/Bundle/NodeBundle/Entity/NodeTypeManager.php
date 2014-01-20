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

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Knp\Bundle\PaginatorBundle\Paginator\Adapter as PaginatorAdapter;
use Zend\Paginator\Paginator;

/**
 * Node Type Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.Zym.com/)
 */
class NodeTypeManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var NodeTypeRepository
     */
    protected $repository;
    
    /*
     * Create a node
     *
     * @param Node $node
     * @return Node
     */
    public function createNodeType(NodeType $nodeType)
    {
        parent::createEntity($nodeType);

        return $nodeType;
    }

    /**
     * Save a node type
     *
     * @param NodeType $nodeType
     * @param boolean $andFlush
     */
    public function saveNodeType(NodeType $nodeType, $andFlush = true)
    {
        parent::saveEntity($nodeType, $andFlush);
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
    public function findNodeTypes(array $criteria = null, $page = 1, $limit = 10, array $orderBy = null)
    {
        return $this->repository->findNodeTypes($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a node type by criteria
     *
     * @param array $criteria
     * @return Node
     */
    public function findNodeTypeBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}