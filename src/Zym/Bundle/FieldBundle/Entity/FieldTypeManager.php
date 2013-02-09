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
 * FieldType Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
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