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
namespace Zym\Bundle\RuntimeConfigBundle\Controller;

use Zym\Bundle\RuntimeConfigBundle\Form;
use Zym\Bundle\RuntimeConfigBundle\Entity;

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
 * Runtime Config Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class ParametersController extends Controller
{
    /**
     * @Route("/", name="zym_runtime_config_parameters")
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        $parameterManager = $this->get('zym_runtime_config.parameter_manager');
        $parameters       = $parameterManager->findParameters($filterBy, $page, $limit, $orderBy);

        return array(
            'parameters' => $parameters
        );
    }
    
    /**
     * Show a parameter
     *
     * @param Entity\Parameter $parameter
     *
     * @Route(
     *     "/{name}/view.{_format}",
     *     name="zym_runtime_config_parameters_show",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+",
     *         "_format" = "html|json"
     *     }
     * )
     * @ParamConverter("parameter", class="ZymRuntimeConfigBundle:Parameter")
     * @Template()
     *
     * @SecureParam(name="parameter", permissions="VIEW")
     */
    public function showAction(Entity\Parameter $parameter)
    {
        return array('parameter' => $parameter);
    }

    /**
     * @Route(
     *     "/add.{_format}",
     *     name="zym_runtime_config_parameters_add",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\RuntimeConfigBundle\Entity\Parameter'))) {
            throw new AccessDeniedException();
        }

        $parameter = new Entity\Parameter();

        $form = $this->createForm(new Form\ParameterType(), $parameter);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $parameterManager = $this->get('zym_runtime_config.parameter_manager');
                $parameterManager->createParameter($parameter);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Successfully created'), 'success');

                return $this->redirect($this->generateUrl('zym_runtime_config_parameters'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{name}/edit", name="zym_runtime_config_parameters_edit")
     * @ParamConverter("parameter", class="ZymRuntimeConfigBundle:Parameter")
     * @Template()
     *
     * @SecureParam(name="parameter", permissions="EDIT")
     */
    public function editAction(Entity\Parameter $parameter)
    {
        $origParameter = clone $parameter;
        $form     = $this->createForm(new Form\ParameterType(), $parameter);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $parameterManager = $this->get('zym_runtime_config.parameter_manager');
                $parameterManager->saveParameter($parameter);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Saved'), 'success');


                return $this->redirect($this->generateUrl('zym_runtime_config_parameters'));
            }
        }

        return array(
            'parameter' => $origParameter,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a parameter
     *
     * @param Entity\Parameter $parameter
     *
     * @Route(
     *     "/{name}",
     *     requirements={ "_method" = "DELETE"}
     * )
     * @Route(
     *     "/{name}/delete.{_format}",
     *     name="zym_runtime_config_parameters_delete",
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
     * @SecureParam(name="parameter", permissions="DELETE")
     */
    public function deleteAction(Entity\Parameter $parameter)
    {
        $origParameter = clone $parameter;

        /* @var $parameterManager Entity\ParameterManager */
        $parameterManager = $this->get('zym_runtime_config.parameter_manager');
        $form        = $this->createForm(new Form\DeleteType(), $parameter);

        $request     = $this->get('request');

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $parameterManager->deleteParameter($parameter);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Parameter Deleted'), 'success');

                return $this->redirect($this->generateUrl('zym_runtime_config_parameters'));
            }
        }

        if ($request->getMethod() == 'DELETE') {
            $parameterManager->deleteParameter($parameter);

            return $this->redirect($this->generateUrl('zym_runtime_config_parameters'));
        }

        return array(
            'parameter' => $origParameter,
            'form' => $form->createView()
        );
    }
}
