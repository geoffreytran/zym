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

namespace Zym\Bundle\UserBundle\Tests\Document;

use Zym\Bundle\UserBundle;

/**
 * Zym User Bundle
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var UserBundle\Entity\UserManager
     */
    private $userManager;
    private $encoderFactory;
    private $algorithm;
    private $usernameCanonicalizer;
    private $emailCanonicalizer;

    protected function setUp()
    {
        $this->encoderFactory        = $this->getMockEncoderFactory();
        $this->algorithm             = 'sha512';
        $this->usernameCanonicalizer = $this->getMockCanonicalizer();
        $this->emailCanonicalizer    = $this->getMockCanonicalizer();
        $this->entityManager         = $this->getMockEntityManager();

        $this->userManager = $this->getUserManager(array(
            $this->encoderFactory,
            $this->algorithm,
            $this->usernameCanonicalizer,
            $this->emailCanonicalizer,
            $this->entityManager,
            'Zym\Bundle\UserBundle\Document\User'
        ));
    }

    protected function tearDown()
    {
        $this->manager               = null;
        $this->encoderFactory        = null;
        $this->algorithm             = null;
        $this->usernameCanonicalizer = null;
        $this->emailCanonicalizer    = null;
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameWithMissingUser()
    {
        $this->userManager->expects($this->once())
            ->method('findUserByUsernameOrEmail')
            ->will($this->returnValue(null));

        $this->userManager->loadUserByUsername('jack');
    }

    public function testLoadUserByUsernameWithEmail()
    {
        $this->userManager->expects($this->once())
            ->method('findUserByUsernameOrEmail')
            ->will($this->returnValue(true));

        $this->userManager->loadUserByUsername('jack@msn.com');
    }

    public function testLoadUserByUsernameWithUsername()
    {
        $this->userManager->expects($this->once())
            ->method('findUserByUsername')
            ->will($this->returnValue(true));

        $this->userManager->loadUserByUsername('jack');
    }


    private function getMockCanonicalizer()
    {
        return $this->getMock('FOS\UserBundle\Util\CanonicalizerInterface');
    }

    private function getMockEncoderFactory()
    {
        return $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
    }

    private function getMockPasswordEncoder()
    {
        return $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
    }

    private function getMockEntityManager()
    {
        $em = $this->getMock(
            'Doctrine\ORM\EntityManager',
            array('getRepository', 'persist', 'getClassMetadata'),
            array(),
            '',
            false
        );

        $metadata = new \stdClass();
        $metadata->name = 'Zym\Bundle\UserBundle\Entity\User';

        $em->expects($this->once())
           ->method('getClassMetadata')
           ->with($this->equalTo('Zym\Bundle\UserBundle\Entity\User'))
           ->will($this->returnValue($metadata));

        return $em;
    }

    private function getUser()
    {
        return $this->getMockBuilder('FOS\UserBundle\Model\User')
            ->getMockForAbstractClass();
    }

    private function getUserManager(array $args)
    {
        return $this->getMockBuilder('Zym\Bundle\UserBundle\Document\UserManager')
            ->setConstructorArgs($args)
            ->setMethods(array('findUserByUsernameOrEmail', 'findUserByUsername'))
            ->getMock();
    }
}