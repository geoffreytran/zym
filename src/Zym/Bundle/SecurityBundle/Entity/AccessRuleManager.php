<?php
namespace Zym\Bundle\SecurityBundle\Entity;

use Zym\Bundle\SecurityBundle\Http\AccessRuleProviderInterface;
use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Access Rule Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class AccessRuleManager extends AbstractEntityManager
                        implements AccessRuleProviderInterface
{
    /**
     * Repository
     *
     * @var AccessRuleRepository
     */
    protected $repository;

    /*
     * Create an access rule
     *
     * @param AccessRule $accessRule
     * @return AccessRule
     */
    public function createAccessRule(AccessRule $accessRule)
    {
        parent::createEntity($accessRule);
        return $accessRule;
    }

    /**
     * Delete an access rule
     *
     * @param AccessRule $accessRule
     */
    public function deleteAccessRule(AccessRule $accessRule)
    {
        parent::deleteEntity($accessRule);
    }

    /**
     * Save an access rule
     *
     * @param AccessRule $accessRule
     * @param boolean $andFlush
     */
    public function saveAccessRule(AccessRule $accessRule, $andFlush = true)
    {
        $this->saveEntity($accessRule, $andFlush);
    }

    /**
     * Find an access rule
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findAccessRules(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findAccessRules($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find an access rule by criteria
     *
     * @param array $criteria
     * @return AccessRule
     */
    public function findAccessRuleBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    /**
     * Get the access rules
     *
     * @return array Array of Zym\Bundle\SecurityBundle\Http\AccessRuleInterface
     */
    public function getRules()
    {
        return $this->repository->findAll();
    }
}