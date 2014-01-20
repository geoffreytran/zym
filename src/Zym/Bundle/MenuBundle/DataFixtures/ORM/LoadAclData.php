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

namespace Zym\Bundle\MenuBundle\DataFixtures\ORM;

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
        /* @var $aclProvider \Symfony\Component\Security\Acl\Model\AclProviderInterface */
        $aclProvider = $this->container->get('security.acl.provider');

        // Menu
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\Menu');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_MASTER);

        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        // Menu Item
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\MenuItem');

            /* @var $acl \Symfony\Component\Security\Acl\Model\AclInterface */
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            /* @var $acl \Symfony\Component\Security\Acl\Model\AclInterface */
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_MASTER);

        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);


        // Routed Menu Item
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\MenuItem\RoutedMenuItem');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_MASTER);

        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        // Static Menu Item
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\MenuItem\StaticMenuItem');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_MASTER);

        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);

        // Section Menu Item
        try {
            $oid = new ObjectIdentity('class', 'Zym\Bundle\MenuBundle\Entity\MenuItem\SectionMenuItem');
            $acl = $aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $e) {
            $acl = $aclProvider->findAcl($oid);
        }

        $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY');
        $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

        $sid = new RoleSecurityIdentity('ROLE_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_MASTER);

        $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
        $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
        $aclProvider->updateAcl($acl);
    }

    /**
     * Get the order in which fixtures will be loaded
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
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