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
 
namespace Zym\Bundle\SecurityBundle\Controller;

use Zym\Bundle\SecurityBundle\Form;
use Zym\Bundle\SecurityBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Acl Roles Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class AclRolesController extends Controller
{
    /**
     * @Route(
     *     ".{_format}", 
     *     name="zym_security_acl_roles",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     * @Template()
     */
    public function listAction()
    {   
        $request = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = array_merge(array('username' => 0), (array)$request->query->get('filterBy'));

        $roleManager = $this->get('zym_security.acl_security_identity_manager');
        $roles       = $roleManager->findAclSecurityIdentities($filterBy, $page, $limit, $orderBy);

        return array(
            'roles' => $roles
        );
    }

    /**
     * @Route("/add", name="zym_security_acl_roles_add")
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\SecurityBundle\Entity\AclSecurityIdentity'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $role = new Entity\AclSecurityIdentity();
        $form = $this->createForm(new Form\AclSecurityIdentityType(), $role);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $roleManager = $this->get('zym_security.acl_security_identity_manager');
                $roleManager->createAclSecurityIdentity($role);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Created the new role successfully.'), 'success');
                     
                return $this->redirect($this->generateUrl('zym_security_acl_roles'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit", name="zym_security_acl_roles_edit")
     * @Template()
     *
     * @SecureParam(name="role", permissions="EDIT")
     */
    public function editAction(Entity\AclSecurityIdentity $role)
    {
        $origNode = clone $role;
        $form     = $this->createForm(new Form\NodeType(), $role);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $roleManager = $this->get('zym_role.role_manager');
                $roleManager->saveNode($role);

                $translator = $this->get('translator');

                $this->get('session')
                     ->getFlashBag()->add('success', $translator->trans('Changes saved!'));


                return $this->redirect($this->generateUrl('backend_roles'));
            }
        }
        
        return array(
            'role' => $origNode,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a role
     *
     * @param Entity\AclSecurityIdentity $role
     *
     * @Route(
     *     "/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     *
     * @Route(
     *     "/{id}/delete.{_format}", 
     *     name="zym_security_acl_roles_delete", 
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
     * @SecureParam(name="role", permissions="DELETE")
     */
    public function deleteAction(Entity\AclSecurityIdentity $role)
    {
        $origNode = clone $role;

        /* @var $roleManager Entity\AclSecurityIdentityManager */
        $roleManager = $this->get('zym_security.acl_security_identity_manager');
        $form        = $this->createForm(new Form\DeleteType(), $role);

        $request     = $this->get('request');
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $roleManager->deleteAclSecurityIdentity($role);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Role Deleted'), 'success');

                return $this->redirect($this->generateUrl('zym_security_acl_roles'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $roleManager->deleteAclSecurityIdentity($role);

            return $this->redirect($this->generateUrl('zym_security_acl_roles'));
        }

        return array(
            'role' => $origNode,
            'form' => $form->createView()
        );
    }
}
