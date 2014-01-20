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
class AclSecurityIdentityManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var AclSecurityIdentityRepository
     */
    protected $repository;

    /*
     * Create a aclSecurityIdentity
     *
     * @param AclSecurityIdentity $aclSecurityIdentity
     * @return AclSecurityIdentity
     */
    public function createAclSecurityIdentity(AclSecurityIdentity $aclSecurityIdentity)
    {
        parent::createEntity($aclSecurityIdentity);
        return $aclSecurityIdentity;
    }

    /**
     * Delete a aclSecurityIdentity
     *
     * @param AclSecurityIdentity $aclSecurityIdentity
     */
    public function deleteAclSecurityIdentity(AclSecurityIdentity $aclSecurityIdentity)
    {
        parent::deleteEntity($aclSecurityIdentity);
    }

    /**
     * Save a aclSecurityIdentity
     *
     * @param AclSecurityIdentity $aclSecurityIdentity
     * @param boolean $andFlush
     */
    public function saveAclSecurityIdentity(AclSecurityIdentity $aclSecurityIdentity, $andFlush = true)
    {
        $this->saveEntity($aclSecurityIdentity, $andFlush);
    }

    /**
     * Find aclSecurityIdentity
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findAclSecurityIdentities(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findAclSecurityIdentities($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a node by criteria
     *
     * @param array $criteria
     * @return Node
     */
    public function findAclSecurityIdentityBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}