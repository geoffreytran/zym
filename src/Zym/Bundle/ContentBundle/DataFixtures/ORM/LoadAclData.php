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

namespace Zym\Bundle\ContentBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;

/**
 * Class LoadAclData
 *
 * @package Zym\Bundle\ContentBundle\DataFixtures\ORM
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
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
        $blockClasses = array(
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\AbstractBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ActionBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\RssBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock',
            'Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\StringBlock'
        );

        $menuClasses = array(
            'Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu',
            'Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode',
            'Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNodeBase'
        );

        $routingClasses = array(
            'Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\RedirectRoute',
            'Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route',

            'Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\MultilangRedirectRoute',
            'Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\MultilangRoute'
        );

        $contentClasses = array(
            'Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page'
        );

        $this->loadAcls($blockClasses);
        $this->loadAcls($menuClasses);
        $this->loadAcls($routingClasses);
        $this->loadAcls($contentClasses);
    }

    private function loadAcls(array $classes)
    {
        /** @var $aclProvider AclProviderInterface */
        $aclProvider = $this->container->get('security.acl.provider');

        foreach ($classes as $class) {
            try {
                $oid = new ObjectIdentity('class', $class);
                $acl = $aclProvider->createAcl($oid);
            } catch (AclAlreadyExistsException $e) {
                $acl = $aclProvider->findAcl($oid);
            }

            $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY');
            $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

            $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_REMEMBERED');
            $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

            $sid = new RoleSecurityIdentity('IS_AUTHENTICATED_FULLY');
            $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

            $sid = new RoleSecurityIdentity('ROLE_USER');
            $acl->insertClassAce($sid, MaskBuilder::MASK_VIEW);

            $sid = new RoleSecurityIdentity('ROLE_ADMIN');
            $acl->insertClassAce($sid, MaskBuilder::MASK_MASTER);

            $sid = new RoleSecurityIdentity('ROLE_SUPER_ADMIN');
            $acl->insertClassAce($sid, MaskBuilder::MASK_IDDQD);
            $aclProvider->updateAcl($acl);
        }
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