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
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */

namespace Zym\Bundle\FrameworkBundle\Entity;

use Zym\Bundle\FrameworkBundle\Model\PageableRepositoryInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Abstract Entity Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
abstract class AbstractEntityManager
{
    /**
     * EntityManager
     *
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Repository
     *
     * @var AbstractEntityRepository
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
     * Security Context
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Construct
     *
     * @param ORM\EntityManager $entityManager
     * @param string $class
     * @param PaginatorAdapter $paginatorAdapter
     * @param MutableAclInterface $aclProvider
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(EntityManager $entityManager, $class,
                                Paginator $paginator,
                                MutableAclProviderInterface $aclProvider,
                                SecurityContextInterface $securityContext = null)
    {
        $this->setEntityManager($entityManager);
        $this->setRepository($entityManager->getRepository($class));

        $metadata    = $entityManager->getClassMetadata($class);
        $this->class = $metadata->name;

        if ($this->getRepository() instanceof PageableRepositoryInterface) {
            $this->getRepository()->setPaginator($paginator);
        }

        $this->setAclProvider($aclProvider);
        
        if ($securityContext) {
            $this->setSecurityContext($securityContext);
        }
    }


    /**
     * Find a node by criteria
     *
     * @param array $criteria
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get the entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Set the entity manager
     *
     * @param EntityManager $entityManager
     * @return AbstractEntityManager
     */
    protected function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set the repository
     *
     * @param EntityRepository $repository
     * @return AbstractEntityManager
     */
    protected function setRepository(EntityRepository $repository)
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
     * @return AbstractEntityManager
     */
    public function setAclProvider(MutableAclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
        return $this;
    }

    /**
     * Get the security context
     *
     * @return SecurityContextInterface
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * Set the security context
     *
     * @param SecurityContextInterface $securityContext
     * @return AbstractEntityManager
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
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
        $em = $this->entityManager;

        $em->beginTransaction();

        try {
            $em->persist($entity);
            $em->flush();

            // Acl
            $aclProvider = $this->aclProvider;
            $oid         = ObjectIdentity::fromDomainObject($entity);

            try {
                $acl         = $aclProvider->createAcl($oid);
                $aclProvider->updateAcl($acl);
            } catch (AclAlreadyExistsException $e) {
                // No need to do anything
            }

            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
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
        $em = $this->entityManager;

        $em->beginTransaction();

        try {
            // Acl
            $aclProvider = $this->aclProvider;
            $oid         = ObjectIdentity::fromDomainObject($entity);
            $acl         = $aclProvider->deleteAcl($oid);

            $em->remove($entity);
            $em->flush();


            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
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
        $em = $this->entityManager;
        $em->persist($entity);

        if ($andFlush) {
            $em->flush();
        }
    }
}