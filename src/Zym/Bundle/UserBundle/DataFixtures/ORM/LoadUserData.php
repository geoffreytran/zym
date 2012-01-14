<?php
namespace Zym\Bundle\UserBundle\DataFixtures\ORM;

use Zym\Bundle\UserBundle\Entity;
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

class LoadUserData extends AbstractFixture
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
     * @param object $manager
     */
    public function load($manager)
    {
        $user = new Entity\User();
        $user->setUsername('root');
        $user->setPlainPassword('admin');
        $user->setEmail('root@localhost');
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $user->addRole('ROLE_USER');
        $user->addRole('ROLE_ADMIN');
        $user->addRole('ROLE_SUPER_ADMIN');
        $user->addRole('ROLE_ALLOWED_TO_SWITCH');

        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->addUser($user);
    }

    /**
     * Get the order in which fixtures will be loaded
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
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