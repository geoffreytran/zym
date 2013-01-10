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

namespace Zym\Bundle\UserBundle\Entity;

use Zym\Bundle\FieldBundle\Entity\FieldManager;
use Zym\Bundle\FrameworkBundle\Model\PageableRepositoryInterface;

use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

use FOS\UserBundle\Doctrine\GroupManager as AbstractGroupManager;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;

/**
 * Group Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 */
class GroupManager extends AbstractGroupManager
{
    /**
     * Repository
     *
     * @var UserRepository
     */
    protected $repository;

    /**
     * Class
     *
     * @var string
     */
    protected $class;

    /**
     * Acl Provider
     *
     * @var MutableAclInterface
     */
    protected $aclProvider;

    /**
     * Constructor.
     *
     * @param ObjectManager           $om
     * @param string                  $class
     * @param PaginatorAdapter $paginatorAdapter
     * @param MutableAclInterface $aclProvider
     * @param FieldManager             $fieldManager
     */
    public function __construct(ObjectManager $om, $class,
                                Paginator $paginator,
                                MutableAclProviderInterface $aclProvider)
    {
        parent::__construct($om, $class);

        $this->setRepository($om->getRepository($class));

        $metadata    = $om->getClassMetadata($class);
        $this->class = $metadata->name;

        if ($this->getRepository() instanceof PageableRepositoryInterface) {
            $this->getRepository()->setPaginator($paginator);
        }

        $this->setAclProvider($aclProvider);
    }

    /*
     * Create a Group
     *
     * @param Group $Group
     * @return Group
     */
    public function addGroup(Group $Group)
    {
        $this->createEntity($Group);

        return $Group;
    }

    /**
     * Save a Group
     *
     * @param Group $Group
     * @param boolean $andFlush
     */
    public function saveGroup(Group $Group, $andFlush = true)
    {
        $this->saveEntity($Group, $andFlush);
    }

    /**
     * Delete a Group
     *
     * @param Group $Group
     */
    public function deleteGroup(GroupInterface $Group)
    {
        $this->deleteEntity($Group);
    }

    /**
     * Find Groups
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return Paginator
     */
    public function findGroups(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findGroups($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a Group by criteria
     *
     * @param array $criteria
     * @return Group
     */
    public function findGroupBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Find a node by criteria
     *
     * @param array $criteria
     * @return GroupManager
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the repository
     *
     * @param ObjectRepository $repository
     * @return GroupManager
     */
    protected function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Get the entity class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Get the acl provider
     *
     * @return MutableAclProviderInterface
     */
    public function getAclProvider()
    {
        return $this->aclProvider;
    }

    /**
     * Set the acl provider
     *
     * @param MutableAclProviderInterface $aclProvider
     * @return GroupManager
     */
    public function setAclProvider(MutableAclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
        return $this;
    }

    /**
     * Create an entity
     *
     * @param object $entity
     * @return object
     */
    protected function createEntity($entity)
    {
        // Persist
        $om = $this->objectManager;

        $om->beginTransaction();

        try {
            $om->persist($entity);
            $om->flush();

            // Acl
            $aclProvider = $this->aclProvider;
            $oid         = ObjectIdentity::fromDomainObject($entity);

            try {
                $acl = $aclProvider->createAcl($oid);
                $aclProvider->updateAcl($acl);
            } catch (AclAlreadyExistsException $e) {

            }


            $om->commit();
        } catch (\Exception $e) {
            $om->rollback();
            throw $e;
        }

        return $entity;
    }

    /**
     * Delete an entity
     *
     * @param object $entity
     * @return object
     */
    protected function deleteEntity($entity)
    {
        // Persist
        $om = $this->objectManager;

        $om->beginTransaction();

        try {
            // Acl
            $aclProvider = $this->aclProvider;
            $oid         = ObjectIdentity::fromDomainObject($entity);
            $acl         = $aclProvider->deleteAcl($oid);

            $om->remove($entity);
            $om->flush();


            $om->commit();
        } catch (\Exception $e) {
            $om->rollback();
            throw $e;
        }

        return $entity;
    }

    /**
     * Save an entity
     *
     * @param object $node
     * @param boolean $andFlush
     */
    protected function saveEntity($entity, $andFlush = true)
    {
        $om = $this->objectManager;
        $om->persist($entity);

        if ($andFlush) {
            $om->flush();
        }
    }
}