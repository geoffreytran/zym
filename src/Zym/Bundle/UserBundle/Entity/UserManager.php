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
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;

use FOS\UserBundle\Entity\UserManager as AbstractUserManager;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;

/**
 * User Manager
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 */
class UserManager extends AbstractUserManager
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
     * Field Manager
     *
     * @var FieldManager
     */
    protected $fieldManager;

    /**
     * Constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param CanonicalizerInterface  $usernameCanonicalizer
     * @param CanonicalizerInterface  $emailCanonicalizer
     * @param EntityManager           $em
     * @param string                  $class
     * @param PaginatorAdapter $paginatorAdapter
     * @param MutableAclInterface $aclProvider
     * @param FieldManager             $fieldManager
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, 
                                CanonicalizerInterface $usernameCanonicalizer, 
                                CanonicalizerInterface $emailCanonicalizer, 
                                EntityManager $em, $class,
                                Paginator $paginator,
                                MutableAclProviderInterface $aclProvider,
                                FieldManager $fieldManager = null)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $em, $class);
        
        $this->setRepository($em->getRepository($class));

        $metadata    = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        if ($this->getRepository() instanceof PageableRepositoryInterface) {
            $this->getRepository()->setPaginator($paginator);
        }

        $this->setAclProvider($aclProvider);
        $this->fieldManager = $fieldManager;
        
    }

    /*
     * Create a user
     *
     * @param User $user
     * @return User
     */
    public function addUser(User $user)
    {
        $this->createEntity($user);

        return $user;
    }

    /**
     * Save a user
     *
     * @param User $user
     * @param boolean $andFlush
     */
    public function saveUser(User $user, $andFlush = true)
    {
        $this->saveEntity($user, $andFlush);
        
        if ($this->fieldManager !== null) {
            $this->fieldManager->saveFields($user->getFields(), $andFlush);
        }
    }

    /**
     * Delete a user
     *
     * @param User $user
     */
    public function deleteUser(UserInterface $user)
    {
        $this->deleteEntity($user);
    }

    /**
     * Find users
     *
     * @param array $criteria
     * @param integer $page
     * @param integer $limit
     * @param array $orderBy
     * @return Paginator
     */
    public function findUsers(array $criteria = null, $page = 1, $limit = 50, array $orderBy = null)
    {
        return $this->repository->findUsers($criteria, $page, $limit, $orderBy);
    }

    /**
     * Find a user by criteria
     *
     * @param array $criteria
     * @return User
     */
    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    
    /**
     * Loads a user by username or email
     *
     * It is strongly discouraged to call this method manually as it bypasses
     * all ACL checks.
     *
     * @param string $username
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        // Allow a user to login with username or e-mail address
        $user = $this->findUserByUsernameOrEmail($username);

        if (!$user) {
            return parent::loadUserByUsername($username);
        }

        return $user;
    }    
    
    /**
     * Find a node by criteria
     *
     * @param array $criteria
     * @return UserManager
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Set the field manager
     *
     * @param FieldManager $fieldManager
     * @return UserManager
     */
    public function setFieldManager(FieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
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
     * @return UserManager
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
     * @return UserManager
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
        $em = $this->em;

        $em->beginTransaction();

        try {
            $em->persist($entity);
            $em->flush();

            // Acl
            $aclProvider = $this->aclProvider;
            $oid         = ObjectIdentity::fromDomainObject($entity);

            try {
                $acl = $aclProvider->createAcl($oid);
                
                // Users shouldn't be able to change there own roles
                $builder = new MaskBuilder();
                $builder->add('view')
                        ->add('edit');
                        
                $mask = $builder->get();
                $acl->insertObjectAce(UserSecurityIdentity::fromAccount($entity), $mask);
                
                $builder = new MaskBuilder();
                $builder->add('delete');
                                        
                $mask = $builder->get();
                $acl->insertObjectAce(UserSecurityIdentity::fromAccount($entity), $mask, 0, false);
                
                $aclProvider->updateAcl($acl);
            } catch (AclAlreadyExistsException $e) {

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
        $em = $this->em;

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
        $em = $this->em;
        $em->persist($entity);

        if ($andFlush) {
            $em->flush();
        }
    }
}