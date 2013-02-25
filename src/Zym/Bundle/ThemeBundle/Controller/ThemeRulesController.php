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

namespace Zym\Bundle\ThemeBundle\Controller;

use Zym\Bundle\ThemeBundle\Form;
use Zym\Bundle\ThemeBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Theme Controls Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class ThemeRulesController extends Controller
{
    /**
     * @Route(
     *     "", name="zym_theme_theme_rules")
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        $themeRuleManager = $this->get('zym_theme.theme_rule_manager');
        $themeRules       = $themeRuleManager->findThemeRules($filterBy, $page, $limit, $orderBy);

        return array(
            'themeRules' => $themeRules
        );
    }

    /**
     * @Route("/add", name="zym_theme_theme_rules_add")
     * @Template()
     */
    public function addAction()
    {
        $themeContext = $this->get('security.context');

        // check for create theme
        if (!$themeContext->isGranted('OPERATOR', new ObjectIdentity('class', 'Zym\\Bundle\\ThemeBundle\\Entity\\ThemeRule'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $themeRule = new Entity\ThemeRule();
        $form = $this->createForm(new Form\ThemeRuleType(), $themeRule);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $themeRuleManager = $this->get('zym_theme.theme_rule_manager');
                $themeRuleManager->createThemeRule($themeRule);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Created the new theme rule successfully.'), 'success');

                return $this->redirect($this->generateUrl('zym_theme_theme_rules'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/edit", name="zym_theme_theme_rules_edit")
     * @Template()
     * @SecureParam(name="themeRule", permissions="EDIT")
     */
    public function editAction(Entity\ThemeRule $themeRule)
    {
        $origThemeRule = clone $themeRule;
        $form     = $this->createForm(new Form\ThemeRuleType(), $themeRule);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $themeRuleManager = $this->get('zym_theme.theme_rule_manager');
                $themeRuleManager->saveThemeRule($themeRule);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Saved'), 'success');


                return $this->redirect($this->generateUrl('zym_theme_theme_rules'));
            }
        }

        return array(
            'themeRule' => $origThemeRule,
            'form'       => $form->createView()
        );
    }

    /**
     * Delete an theme rule
     *
     *
     * @Route(
     *     "/{id}",
     *     requirements={ "_method" = "DELETE"}
     * )
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_theme_theme_rules_delete",
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
     * @SecureParam(name="themeRule", permissions="DELETE")
     */
    public function deleteAction(Entity\ThemeRule $themeRule)
    {
        $origThemeRule = clone $themeRule;

        /* @var $themeRuleManager Entity\ThemeRuleManager */
        $themeRuleManager = $this->get('zym_theme.theme_rule_manager');

        $form        = $this->createForm(new Form\DeleteType(), $themeRule);

        $request     = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $themeRuleManager->deleteThemeRule($themeRule);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Theme rule deleted.'), 'success');

                return $this->redirect($this->generateUrl('zym_theme_theme_rules'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $themeRuleManager->deleteThemeRule($themeRule);

            return $this->redirect($this->generateUrl('zym_theme_theme_rules'));
        }

        return array(
            'themeRule' => $origThemeRule,
            'form'       => $form->createView()
        );
    }
}
