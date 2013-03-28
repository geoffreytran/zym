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
use Symfony\Component\Security\Acl\Domain\AclCollectionCache;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Knp\Component\Pager\Paginator;

/**
 * Abstract Entity Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
abstract class AbstractEntityManager
{
    /**
     * ObjectManager
     *
     * @var ObjectManager
     */
    protected $objectManager;

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
     * Acl Collection Cache
     *
     * @var AclCollectionCache
     */
    protected $aclCollectionCache;

    /**
     * Security Context
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Construct
     *
     * @param ObjectManager $objectManager
     * @param string $class
     * @param PaginatorAdapter $paginatorAdapter
     * @param MutableAclInterface $aclProvider
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(ObjectManager $objectManager, $class,
                                Paginator $paginator,
                                MutableAclProviderInterface $aclProvider,
                                SecurityContextInterface $securityContext = null,
                                AclCollectionCache $aclCollectionCache = null)
    {
        $this->setObjectManager($objectManager);
        $this->setRepository($objectManager->getRepository($class));

        $metadata    = $objectManager->getClassMetadata($class);
        $this->class = $metadata->name;

        if ($this->getRepository() instanceof PageableRepositoryInterface) {
            $this->getRepository()->setPaginator($paginator);
        }

        $this->setAclProvider($aclProvider);

        if ($securityContext) {
            $this->setSecurityContext($securityContext);
        }

        if ($aclCollectionCache) {
            $this->setAclCollectionCache($aclCollectionCache);
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
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * Set the object manager
     *
     * @param ObjectManager $objectManager
     * @return AbstractEntityManager
     */
    protected function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        return $this;
    }

    /**
     * Get the entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->objectManager;
    }

    /**
     * Set the entity manager
     *
     * @param EntityManager $entityManager
     * @return AbstractEntityManager
     */
    protected function setEntityManager(EntityManager $entityManager)
    {
        return $this->setObjectManager($entityManager);
    }

    /**
     * Get the entity repository
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
     * Get the acl collection cache
     *
     * @return AclCollectionCache
     */
    public function getAclCollectionCache()
    {
        return $this->aclCollectionCache;
    }

    /**
     * Set the acl collection cache
     *
     * @param AclCollectionCache $aclCollectionCache
     * @return AbstractEntityManager
     */
    public function setAclCollectionCache(AclCollectionCache $aclCollectionCache)
    {
        $this->aclCollectionCache = $aclCollectionCache;
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
        $em = $this->objectManager;

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
        $em = $this->objectManager;

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
        if ($this->objectManager->getUnitOfWork()->getEntityState($entity) == \Doctrine\ORM\UnitOfWork::STATE_NEW) {
            return $this->createEntity($entity, $andFlush);
        } else {
            $em = $this->objectManager;
            $em->persist($entity);

            if ($andFlush) {
                $em->flush();
            }
        }
    }

    /**
     * Preload acls for entities
     *
     * @param Collection $entities
     */
    protected function loadAcls($entities)
    {
        $aclCollectionCache = $this->getAclCollectionCache();

        try {
            if ($aclCollectionCache) {
                $securityContext    = $this->getSecurityContext();

                $sortedEntities = array();
                foreach ($entities as $entity) {
                    $sortedEntities[get_class($entity)][] = $entity;
                }

                foreach ($sortedEntities as $entitiesGroup) {
                    if ($securityContext->getToken() !== null) {
                        $aclCollectionCache->cache($entitiesGroup, array($securityContext->getToken()));
                    } else {
                        $aclCollectionCache->cache($entitiesGroup);
                    }
                }
            }
        } catch (\Symfony\Component\Security\Acl\Exception\NotAllAclsFoundException $e) {
            // At least we tried...
        } catch (\Symfony\Component\Security\Acl\Exception\AclNotFoundException $e) {
            // At least we tried...
        }
    }
}