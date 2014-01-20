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
 * Field Manager
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class FieldManager extends AbstractEntityManager
{
    /**
     * Repository
     *
     * @var FieldRepository
     */
    protected $repository;

    /*
     * Create a field
     *
     * @param Field $field
     * @return Field
     */
    public function createField(Field $field)
    {
        parent::createEntity($field);

        return $field;
    }

    /**
     * Save a field
     *
     * @param Field $field
     * @param boolean $andFlush
     */
    public function saveField(Field $field, $andFlush = true)
    {
        parent::saveEntity($field, $andFlush);
    }

    /**
     * Save a field
     *
     * @param object $fields
     * @param boolean $andFlush
     */
    public function saveFields($fields, $andFlush = true)
    {
        $em = $this->objectManager;

        if ($fields instanceof FieldableInterface) {
            $fields = $fields->getFields();
        }

        foreach ($fields as $field) {
            if ($field instanceof \ArrayAccess || is_array($field)) {
                $this->saveFields($field, false);
                continue;
            }

            $this->saveField($field, false);
        }

        if ($andFlush) {
            $em->flush();
        }
    }

    /**
     * Delete a field
     *
     * @param Field $field
     */
    public function deleteField(Field $field)
    {
        parent::deleteEntity($field);
    }

    /**
     * Find fields
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return PaginationInterface
     */
    public function findFields(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findFields($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a field by criteria
     *
     * @param array $criteria
     * @return Field
     */
    public function findFieldBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}