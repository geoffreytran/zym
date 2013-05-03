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
 * Acl Object Aces Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class AclObjectAcesController extends Controller
{
    /**
     * @Route(
     *     "{type}/{identifier}.{_format}",
     *     name="zym_security_acl_object_aces",
     *     defaults = { "_format" = "html" }
     * )
     * @Template()
     */
    public function listAction($identifier, $type)
    {
        $request = $this->get('request');
        $page    = $request->query->get('page', 1);

        $aclProvider = $this->get('security.acl.provider');

        $oid = new ObjectIdentity($identifier, $type);
        $acl = $aclProvider->findAcl($oid);

        if (!$acl) {
            throw $this->createNotFoundException('Index does not exist');
        }

        return array(
            'acl' => $acl,
            'oid' => $oid
        );
    }

    /**
     * @Route("/add/{classType}", name="zym_security_acl_entries_add")
     * @Template()
     */
    public function addAction(Entity\AclClass $aclClass)
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('OPERATOR', new ObjectIdentity('class', $aclClass->getClassType()))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $aclProvider = $this->get('security.acl.provider');

        $oid         = new ObjectIdentity('class', $aclClass->getClassType());
        $acl         = $aclProvider->findAcl($oid);
        if (!$acl) {
            throw $this->createNotFoundException('Index does not exist');
        }

        $form = $this->createForm(new Form\AclEntryType(), $aclClass);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $acl->insertClassAce($index, $classAce->getMask());

                $aclProvider->updateAcl($acl);
                return $this->redirect($this->generateUrl('zym_security_acl_entries'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{classType}/edit/{index}", name="zym_security_acl_entries_edit")
     * @Template()
     *
     * @SecureParam(name="aclClass", permissions="EDIT")
     */
    public function editAction(Entity\AclClass $aclClass, $index)
    {
        $origAclClass = clone $aclClass;

        $aclProvider = $this->get('security.acl.provider');

        $oid         = new ObjectIdentity('class', $aclClass->getClassType());
        $acl         = $aclProvider->findAcl($oid);

        $classAces = $acl->getClassAces();
        if (!isset($classAces[$index])) {
            throw $this->createNotFoundException('Index does not exist');
        }

        $classAce = clone $classAces[$index];

        $form    = $this->createForm(new Form\AclEntryType(), $classAce);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $acl->updateClassAce($index, $classAce->getMask());

                $aclProvider->updateAcl($acl);

                return $this->redirect($this->generateUrl('zym_security_acl_entries'));
            }
        }

        return array(
            'aclClass' => $origAclClass,
            'index'    => $index,
            'form'     => $form->createView()
        );
    }

    /**
     * Delete a aclClass
     *
     * @param Entity\AclClass $aclClass
     *
     * @Route(
     *     "{type}/{identifier}",
     *     requirements={ "_method" = "DELETE"}
     * )
     *
     * @Route(
     *     "{type}/{identifier}/delete.{_format}",
     *     name="zym_security_acl_object_aces_delete",
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
     * SecureParam(name="aclClass", permissions="DELETE")
     */
    public function deleteAction($identifier, $type)
    {
        $origNode = clone $aclClass;

        /* @var $aclClassManager Entity\AclClassManager */
        $aclClassManager = $this->get('zym_security.acl_security_identity_manager');
        $form        = $this->createForm(new Form\DeleteType(), $aclClass);

        $request     = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $aclClassManager->deleteAclClass($aclClass);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Role Deleted'), 'success');

                return $this->redirect($this->generateUrl('zym_security_acl_entries'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $aclClassManager->deleteNode($aclClass);

            return $this->redirect($this->generateUrl('zym_security_acl_entries'));
        }

        return array(
            'aclClass' => $origNode,
            'form' => $form->createView()
        );
    }
}
