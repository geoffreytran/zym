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
 * Access Controls Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class AccessRulesController extends Controller
{
    /**
     * @Route(
     *     "/", name="zym_security_access_rules")
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        $accessRuleManager = $this->get('zym_security.access_rule_manager');
        $accessRules       = $accessRuleManager->findAccessRules($filterBy, $page, $limit, $orderBy);

        return array(
            'accessRules' => $accessRules
        );
    }

    /**
     * @Route("/add", name="zym_security_access_rules_add")
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('OPERATOR', new ObjectIdentity('class', 'Zym\\Bundle\\SecurityBundle\\Entity\\AccessRule'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $accessRule = new Entity\AccessRule();
        $form = $this->createForm(new Form\AccessRuleType(), $accessRule);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $accessRuleManager = $this->get('zym_security.access_rule_manager');
                $accessRuleManager->createAccessRule($accessRule);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Created the new access rule successfully.'), 'success');

                return $this->redirect($this->generateUrl('zym_security_access_rules'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit", name="zym_security_access_rules_edit")
     * @Template()
     * @SecureParam(name="accessRule", permissions="EDIT")
     */
    public function editAction(Entity\AccessRule $accessRule)
    {
        $origAccessRule = clone $accessRule;
        $form     = $this->createForm(new Form\AccessRuleType(), $accessRule);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $accessRuleManager = $this->get('zym_security.access_rule_manager');
                $accessRuleManager->saveAccessRule($accessRule);

                $translator = $this->get('translator');

                $this->get('session')
                     ->getFlashBag()->add('success', $translator->trans('Changes saved!'));


                return $this->redirect($this->generateUrl('zym_security_access_rules'));
            }
        }

        return array(
            'accessRule' => $origAccessRule,
            'form'       => $form->createView()
        );
    }

    /**
     * Delete an access rule
     *
     *
     * @Route(
     *     "/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_security_access_rules_delete",
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
     * @SecureParam(name="accessRule", permissions="DELETE")
     */
    public function deleteAction(Entity\AccessRule $accessRule)
    {
        $origAccessRule = clone $accessRule;

        /* @var $accessRuleManager Entity\AccessRuleManager */
        $accessRuleManager = $this->get('zym_security.access_rule_manager');

        $form        = $this->createForm(new Form\DeleteType(), $accessRule);

        $request     = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $accessRuleManager->deleteAccessRule($accessRule);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Access rule deleted.'), 'success');

                return $this->redirect($this->generateUrl('zym_security_access_rules'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $accessRuleManager->deleteAccessRule($accessRule);

            return $this->redirect($this->generateUrl('zym_security_access_rules'));
        }

        return array(
            'accessRule' => $origAccessRule,
            'form'       => $form->createView()
        );
    }
}
