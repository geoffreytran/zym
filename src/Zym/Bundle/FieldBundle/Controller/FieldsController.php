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
 * Fields Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class FieldsController extends Controller
{
    /**
     * @Route("/class/{class}/{id}", name="zym_field_fields")
     * @Template()
     */
    public function listAction($class, $id)
    {
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');
        
        $doctrine   = $this->get('doctrine');
        $repository = $doctrine->getRepository($class);
        $fieldable  = $repository->find($id);
        $fieldConfigs = $fieldable->getFieldConfigs();

        //$fieldConfigManager = $this->get('zym_field.field_config_manager');
        //$fieldConfigs       = $fieldConfigManager->findFieldConfigs($filterBy, $page, $limit, $orderBy);

        return array(
            'fieldConfigs' => $fieldConfigs
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
            $form->bindRequest($request);
    
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
