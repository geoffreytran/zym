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

namespace Zym\Bundle\FieldBundle\Controller;

use Zym\Bundle\FieldBundle\Form;
use Zym\Bundle\FieldBundle\Entity;

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
 * Field Types Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class FieldTypesController extends Controller
{
    /**
     * @Route("/", name="zym_field_field_types")
     * @Template()
     */
    public function listAction()
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');


        $fieldTypeManager = $this->get('zym_field.field_type_manager');
        $fieldTypes       = $fieldTypeManager->findFieldTypes($filterBy, $page, $limit, $orderBy);

        return array(
            'fieldTypes' => $fieldTypes
        );
    }

    /**
     * @Route(
     *     "/{id}/edit.{_format}", 
     *     name="zym_field_field_configs_edit",
     *     defaults    = { "_format" = "html" },
     *     requirements = { "id" = "\d+" }
     *     
     * )
     * @Template()
     */
    public function editAction(Entity\FieldConfig $fieldConfig)
    {
        $origFieldConfig = clone $fieldConfig;
        $form            = $this->createForm(new Form\FieldConfigType(), $fieldConfig);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                /* @var $fieldConfigManager Entity\FieldConfigManager */
                $fieldConfigManager = $this->get('zym_field.field_config_manager');
                $fieldConfigManager>saveFieldConfig($fieldConfig);

                return $this->redirect($this->generateUrl('zym_node_node_types'));
            }
        }

        return array(
            'fieldConfig' => $origFieldConfig,
            'form'        => $form->createView()
        );
    }
}
