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
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
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