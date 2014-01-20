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

namespace Zym\Bundle\SecurityBundle\Entity;

use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Acl Security Identity Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2014 Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class AclClassManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var AclClassRepository
     */
    protected $repository;

    /*
     * Create a aclClass
     *
     * @param AclClass $aclClass
     * @return AclClass
     */
    public function createAclClass(AclClass $aclClass)
    {
        parent::createEntity($aclClass);
        return $aclClass;
    }

    /**
     * Delete a aclClass
     *
     * @param AclClass $aclClass
     */
    public function deleteAclClass(AclClass $aclClass)
    {
        parent::deleteEntity($aclClass);
    }

    /**
     * Save a aclClass
     *
     * @param AclClass $aclClass
     * @param boolean $andFlush
     */
    public function saveAclClass(AclClass $aclClass, $andFlush = true)
    {
        $this->saveEntity($aclClass, $andFlush);
    }

    /**
     * Find aclClass
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findAclClasses(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findAclClasses($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a node by criteria
     *
     * @param array $criteria
     * @return Node
     */
    public function findAclClassBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}