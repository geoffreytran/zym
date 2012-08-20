<?php
namespace Zym\Bundle\SecurityBundle\DataFixtures\ORM;

use Zym\Bundle\SecurityBundle\Entity;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

class LoadAclData extends AbstractFixture
                  implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // Roles

        $identity = new Entity\AclSecurityIdentity();
        $identity->setIdentifier('IS_AUTHENTICATED_ANONYMOUSLY');
        $identity->setUsername(false);
        $manager->persist($identity);

        $identity = new Entity\AclSecurityIdentity();
        $identity->setIdentifier('IS_AUTHENTICATED_FULLY');
        $identity->setUsername(false);
        $manager->persist($identity);

        $identity = new Entity\AclSecurityIdentity();
        $identity->setIdentifier('IS_AUTHENTICATED_REMEMBERED');
        $identity->setUsername(false);
        $manager->persist($identity);

        $identity = new Entity\AclSecurityIdentity();
        $identity->setIdentifier('ROLE_ALLOWED_TO_SWITCH');
        $identity->setUsername(false);
        $manager->persist($identity);

        $identity = new Entity\AclSecurityIdentity();
        $identity->setIdentifier('ROLE_USER');
        $identity->setUsername(false);
        $manager->persist($identity);

        $aclProvider = $this->container->get('security.acl.provider');

        // AclClass
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\SecurityBundle\Entity\AclClass');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        // insert ACEs for the super admin
        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        // AclEntry
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\SecurityBundle\Entity\AclEntry');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        // insert ACEs for the super admin
        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        // AclSecurityIdentity
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\SecurityBundle\Entity\AclSecurityIdentity');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        // insert ACEs for the super admin
        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        // AccessRule
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\SecurityBundle\Entity\AccessRule');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        // insert ACEs for the super admin
        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        $manager->flush();
    }

    /**
     * Get the order in which fixtures will be loaded
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0; // the order in which fixtures will be loaded
    }

    /**
     * Set the container
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}