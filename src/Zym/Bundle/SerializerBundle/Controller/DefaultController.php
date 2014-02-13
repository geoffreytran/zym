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

namespace Zym\Bundle\SerializerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @package Zym\Bundle\SerializerBundle\Controller
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ZymSerializerBundle:Default:index.html.twig', array('name' => $name));
    }
}