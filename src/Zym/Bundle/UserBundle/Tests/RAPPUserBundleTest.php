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

namespace Zym\Bundle\UserBundle\Tests;

use Zym\Bundle\UserBundle;

/**
 * Zym User Bundle
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class ZymUserBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var UserBundle\ZymUserBundle
     */
    private $bundle;

    protected function setUp()
    {
        $this->bundle = new UserBundle\ZymUserBundle();
    }

    protected function tearDown()
    {
        $this->bundle = null;
    }

    public function testGetParentReturnsFOS()
    {
        $this->assertEquals($this->bundle->getParent(), 'FOSUserBundle');
    }
}