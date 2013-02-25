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
 * @copyright Copyright (c) 2011 Zym. (http://www.rapp.com/)
 */
class NodeTypesController extends Controller
{
    /**
     * @Route(
     *     ".{_format}", 
     *     name="zym_node_node_types",
     *     defaults = {
     *         "_format" = "html"
     *     }
     * )
     *
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 25);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');
        
        /* @var $nodeTypeManager Entity\NodeTypeManager */
        $nodeTypeManager = $this->get('zym_node.node_type_manager');
        $nodeTypes       = $nodeTypeManager->findNodeTypes($filterBy, $page, $limit, $orderBy);

        return array(
            'nodeTypes' => $nodeTypes
        );
    }

    /**
     * @Route("/add", name="zym_node_node_types_add")
     * @Template()
     */
    public function addAction()
    {
        $nodeType = new Entity\NodeType();

        $form = $this->createForm(new Form\NodeTypeType(), $nodeType);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $nodeTypeManager Entity\NodeTypeManager */
                $nodeTypeManager = $this->get('zym_node.node_type_manager');
                $nodeTypeManager->createNodeType($nodeType);

                return $this->redirect($this->generateUrl('zym_node_node_types'));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/{type}/edit", name="zym_node_node_types_edit")
     * @Template()
     */
    public function editAction(Entity\NodeType $nodeType)
    {
        $origNodeType = clone $nodeType;
        $form         = $this->createForm(new Form\NodeTypeType(), $nodeType);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $nodeTypeManager Entity\NodeTypeManager */
                $nodeTypeManager = $this->get('zym_node.node_type_manager');
                $nodeTypeManager->saveNodeType($nodeType);

                return $this->redirect($this->generateUrl('zym_node_node_types'));
            }
        }

        return array(
            'nodeType' => $origNodeType,
            'form'     => $form->createView()
        );
    }
    
    /**
     * Delete a node type
     *
     * @param Entity\NodeType $nodeType
     *
     * @Route(
     *     "/{type}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route(
     *     "/{type}/delete.{_format}", 
     *     name="zym_node_node_types_delete", 
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
     * @SecureParam(name="nodeType", permissions="DELETE")
     */
    public function deleteAction(Entity\NodeType $nodeType)
    {
        $origNodeType = clone $nodeType;

        /* @var $nodeManager Entity\NodeTypeManager */
        $nodeManager = $this->get('zym_node.node_type_manager');
        $form        = $this->createForm(new Form\DeleteType(), $nodeType);

        $request     = $this->get('request');
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $nodeManager->deleteNodeType($nodeType);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Content Type Deleted'), 'success');

                return $this->redirect($this->generateUrl('zym_node_node_types'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $nodeManager->deleteNodeType($nodeType);

            return $this->redirect($this->generateUrl('zym_node_node_types'));
        }

        return array(
            'nodeType' => $origNodeType,
            'form' => $form->createView()
        );
    }
}
