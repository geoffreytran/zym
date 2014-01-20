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

namespace Zym\Bundle\FieldBundle\Entity;

use Zym\Bundle\FieldBundle\FieldableInterface;
use Zym\Bundle\FrameworkBundle\Entity\AbstractEntityManager;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * FieldConfig Manager
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class FieldConfigManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var FieldConfigRepository
     */
    protected $repository;

    /*
     * Create a field config
     *
     * @param FieldConfig $fieldConfig
     * @return FieldConfig
     */
    public function createFieldConfig(FieldConfig $fieldConfig)
    {
        parent::createEntity($fieldConfig);

        return $fieldConfig;
    }

    /**
     * Save a field config
     *
     * @param object $fieldConfig
     * @param boolean $andFlush
     */
    public function saveFieldConfigs($fieldConfig, $andFlush = true)
    {
        parent::saveEntity($fieldConfig, $andFlush);
    }

    /**
     * Delete a field config
     *
     * @param FieldConfig $fieldConfig
     */
    public function deleteFieldConfig(FieldConfig $fieldConfig)
    {
        parent::deleteEntity($fieldConfig);
    }

    /**
     * Find fields configs
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findFieldConfigs(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findFieldConfigs($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a field config by criteria
     *
     * @param array $criteria
     * @return FieldConfig
     */
    public function findFieldConfigBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}