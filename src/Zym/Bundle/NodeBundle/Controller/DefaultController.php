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

namespace Zym\Bundle\NodeBundle\Controller;

use Zym\Bundle\NodeBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Default Controller
 *
 * @package Zym\Bundle\UserBundle
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class DefaultController extends Controller
{
    /**
     * Route("/{path}", name="zym_node_content")
     * @Template()
     */
    public function indexAction($path = null)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        return array();
    }

    /**
     * @Route(
     *     "/node/{id}.{_format}",
     *     name        = "zym_node",
     *     defaults    = { "_format" = "html" }
     * )
     *
     * @SecureParam(name="node", permissions="VIEW")
     */
    public function nodeAction(Entity\Node $node)
    {
        $request = $this->container->get('request');

        $template = 'ZymNodeBundle:Default:node%s.%s.%s';
        $format   = $request->getRequestFormat();
        $engine   = 'twig';

        try {
            // Try to render a node type specific template
            $view     = sprintf($template, $node->getType()->getType(), $format, $engine);
            $response = $this->render($view, array(
                'node' => $node
            ));
        } catch (\InvalidArgumentException $e) {
            // Render default
            $view     = sprintf($template, '', $format, $engine);

            try {
                $response = $this->render($view, array(
                    'node' => $node
                ));
            } catch (\InvalidArgumentException $e) {
                throw $this->createNotFoundException('Invalid template: ' . $view, $e);
            }
        }

        return $response;
    }
}