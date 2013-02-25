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

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Acl Entries Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class AclEntriesController extends Controller
{
    /**
     * @Route(
     *     ".{_format}",
     *     name="zym_security_acl_entries",
     *     defaults = { "_format" = "html" }
     * )
     * @Template()
     */
    public function listAction()
    {
        $request = $this->get('request');
        $page    = $request->query->get('page', 1);

        $aclClassManager = $this->get('zym_security.acl_class_manager');
        $aclClasses      = $aclClassManager->findAclClasses(array(), $page);

        $aclProvider = $this->get('security.acl.provider');

        $oids = array();
        foreach ($aclClasses as $aclClass) {
            $oid = new ObjectIdentity('class', $aclClass->getClassType());

            try {
                $aclProvider->findAcl($oid);
            } catch (AclNotFoundException $e) {
                // Missing class level entry, add it
                $acl = $aclProvider->createAcl($oid);
                $aclProvider->updateAcl($acl);
                continue;
            }

            $oids[] = $oid;
        }

        $acls = $aclProvider->findAcls($oids);

        return array(
            'aclClasses' => $aclClasses,
            'acls'       => $acls
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

        $classAce = new Entity\AclEntry();
        $form = $this->createForm(new Form\AclEntryType(), $classAce);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $acl->insertClassAce(new RoleSecurityIdentity($classAce->getSecurityIdentity()),
                                    $classAce->getMask(), 0, $classAce->getMask(), $classAce->getStrategy());

                $aclProvider->updateAcl($acl);
                return $this->redirect($this->generateUrl('zym_security_acl_entries'));
            }
        }

        return array(
            'aclClass' => $aclClass,
            'form'     => $form->createView(),
        );
    }

    /**
     * @Route("/{classType}/{index}/edit", name="zym_security_acl_entries_edit")
     * @Template()
     *
     * @SecureParam(name="aclClass", permissions="OPERATOR")
     */
    public function editAction(Entity\AclClass $aclClass, $index)
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('OPERATOR', new ObjectIdentity('class', $aclClass->getClassType()))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

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
            'form' => $form->createView()
        );
    }

    /**
     * Delete a aclClass
     *
     * @param Entity\AclClass $aclClass
     *
     * @Route(
     *     "/{classType}/{index}",
     *     requirements={ "_method" = "DELETE"}
     * )
     *
     * @Route(
     *     "/{classType}/{index}/delete.{_format}",
     *     name="zym_security_acl_entries_delete",
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
     * @SecureParam(name="aclClass", permissions="DELETE")
     */
    public function deleteAction(Entity\AclClass $aclClass, $index)
    {
        $origAclClass = clone $aclClass;

        $aclProvider = $this->get('security.acl.provider');

        $oid         = new ObjectIdentity('class', $aclClass->getClassType());
        /* @var $acl \Symfony\Component\Security\Acl\Domain\Acl */
        $acl         = $aclProvider->findAcl($oid);

        $classAces = $acl->getClassAces();
        if (!isset($classAces[$index])) {
            throw $this->createNotFoundException('Index does not exist');
        }

        $classAce = clone $classAces[$index];

        $form        = $this->createForm(new Form\DeleteType(), $classAce);
        $request     = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $acl->deleteClassAce($index);
                $aclProvider->updateAcl($acl);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Acl Entry Deleted'), 'success');

                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $acl->deleteClassAce($index);
            $aclProvider->updateAcl($acl);

            return $this->redirect($this->generateUrl('zym_security_acl_entries'));
        }

        return array(
            'aclClass' => $origAclClass,
            'index'    => $index,
            'form'     => $form->createView()
        );
    }
}
