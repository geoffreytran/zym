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

namespace Zym\Bundle\MenuBundle\Controller;

use Zym\Bundle\MenuBundle\Form;
use Zym\Bundle\MenuBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class SectionsController
 *
 * @package Zym\Bundle\MenuBundle\Controller
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class SectionsController extends Controller
{
    /**
     * @Route(
     *     "{id}.{_format}",
     *     name="zym_menu_categories",
     *     defaults = { "_format" = "html" }
     * )
     * @Template()
     */
    public function listAction(Entity\MenuItem $menuItem)
    {
        return array(
            'menuItem' => $menuItem
        );
    }

}