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
 * FieldType Manager
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class FieldTypeManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var FieldTypeRepository
     */
    protected $repository;

    /*
     * Create a field type
     *
     * @param FieldType $fieldType
     * @return FieldType
     */
    public function createFieldType(FieldType $fieldType)
    {
        parent::createEntity($fieldType);

        return $fieldType;
    }

    /**
     * Save a field type
     *
     * @param object $fieldTypes
     * @param boolean $andFlush
     */
    public function saveFieldTypes($fieldTypes, $andFlush = true)
    {
        $em = $this->entityManager;

        if ($andFlush) {
            $em->flush();
        }
    }

    /**
     * Delete a field type
     *
     * @param FieldType $fieldType
     */
    public function deleteFieldType(FieldType $fieldType)
    {
        parent::deleteEntity($fieldType);
    }

    /**
     * Find fields type
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findFieldTypes(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findFieldTypes($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a field type by criteria
     *
     * @param array $criteria
     * @return FieldType
     */
    public function findFieldTypeBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}