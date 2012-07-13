<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
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
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
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
     * @param object $fieldConfigs
     * @param boolean $andFlush
     */
    public function saveFieldConfigs($fieldConfigs, $andFlush = true)
    {
        $em = $this->entityManager;

        if ($andFlush) {
            $em->flush();
        }
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
        return $this->repository->findFieldConfigs($critera, $page, $limit, $orderBy);
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