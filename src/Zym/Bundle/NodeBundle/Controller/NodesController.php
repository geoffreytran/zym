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
namespace Zym\Bundle\NodeBundle\Controller;

use Zym\Bundle\NodeBundle\Form;
use Zym\Bundle\NodeBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * NodeTypes Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class NodesController extends Controller
{
    /**
     * @Route("/", name="zym_nodes")
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        $nodeManager = $this->get('zym_node.node_manager');
        $nodes       = $nodeManager->findNodes($filterBy, $page, $limit, $orderBy);

        return array(
            'nodes' => $nodes
        );
    }

    /**
     * @Route(
     *     "/{id}.{_format}",
     *     name        = "zym_nodes_node",
     *     defaults    = { "_format" = "html" },
     *     requirements = { "id" = "\d+" }
     * )
     *
     * @SecureParam(name="node", permissions="VIEW")
     */
    public function nodeAction(Entity\Node $node)
    {
        $request = $this->container->get('request');

        $template = 'ZymNodeBundle:Nodes:node%s.%s.%s';
        $format   = $request->getRequestFormat();
        $engine   = 'twig';


        try {
            // Try to render a node type specific template
            $view     = sprintf($template, $node->getNodeType()->getType(), $format, $engine);
            $response = $this->render($view, array('node' => $node));
        } catch (\InvalidArgumentException $e) {
            // Render default
            $view     = sprintf($template, '', $format, $engine);

            try {
                $response = $this->render($view, array('node' => $node));
            } catch (\InvalidArgumentException $e) {
                throw $this->createNotFoundException('Invalid template: ' . $view, $e);
            }
        }

        return $response;
    }

    /**
     * @Route(
     *     "/add.{_format}/{type}",
     *     name="zym_nodes_add",
     *     defaults={
     *         "type" = false,
     *         "_format" = "html"
     *     }
     * )
     * @Template()
     * @SecureParam(name="nodeType", permissions="VIEW")
     *
     * @ParamConverter(name="nodeType", options={"id": "type"})
     */
    public function addAction(Entity\NodeType $nodeType = null)
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\NodeBundle\Entity\Node'))) {
            throw new AccessDeniedException();
        }

        if ($nodeType) {
            $node = new Entity\Node($nodeType);

            $form = $this->createForm(new Form\NodeType(), $node);

            $request = $this->get('request');
            if ($request->getMethod() == 'POST') {
                $form->bind($request);

                if ($form->isValid()) {
                    $nodeManager = $this->get('zym_node.node_manager');
                    $nodeManager->createNode($node);

                    $translator = $this->get('translator');

                    $this->get('session')
                         ->getFlashBag()->add('success', $translator->trans('Added successfully!'));

                    return $this->redirect($this->generateUrl('zym_nodes'));
                }
            }

            return array(
                'form'     => $form->createView(),
                'nodeType' => $nodeType
            );
        }

        $nodeTypeManager = $this->get('zym_node.node_type_manager');
        $nodeTypes       = $nodeTypeManager->findNodeTypes();

        return array('nodeTypes' => $nodeTypes);
    }

    /**
     * @Route("/{id}/edit", name="zym_nodes_edit")
     * @ParamConverter("node", class="ZymNodeBundle:Node")
     * @Template()
     *
     * @SecureParam(name="node", permissions="EDIT")
     */
    public function editAction(Entity\Node $node)
    {
        $origNode = clone $node;
        $form     = $this->createForm(new Form\NodeType(), $node);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $nodeManager = $this->get('zym_node.node_manager');
                $nodeManager->saveNode($node);

                $translator = $this->get('translator');

                $this->get('session')
                     ->getFlashBag()->add('success', $translator->trans('Changes saved!'));


                return $this->redirect($this->generateUrl('zym_nodes'));
            }
        }

        return array(
            'node' => $origNode,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a node
     *
     * @param Entity\Node $node
     *
     * @Route(
     *     "/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_node_nodes_delete",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "_format" = "html|json|ajax"
     *     }
     * )
     *
     * @Template()
     *
     * @SecureParam(name="node", permissions="DELETE")
     */
    public function deleteAction(Entity\Node $node)
    {
        $origNode = clone $node;

        /* @var $nodeManager Entity\NodeManager */
        $nodeManager = $this->get('zym_node.node_manager');
        $form        = $this->createForm(new Form\DeleteType(), $node);

        $request     = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $nodeManager->deleteNode($node);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Content Deleted'), 'success');

                return $this->redirect($this->generateUrl('zym_nodes'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $nodeManager->deleteNode($node);

            return $this->redirect($this->generateUrl('zym_nodes'));
        }

        return array(
            'node' => $origNode,
            'form' => $form->createView()
        );
    }
}
