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

namespace Zym\Bundle\UserBundle\Controller;

use FOS\RestBundle\View\View as ViewResponse;
use FOS\RestBundle\Util\Codes;
use Zym\Bundle\UserBundle\Form;
use Zym\Bundle\UserBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Groups Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class GroupsController extends Controller
{
    /**
     * @Route(
     *     ".{_format}",
     *     name="zym_user_groups",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET"}
     * )
     * @View()
     *
     * @ApiDoc(
     *     description="Returns a list user groups",
     *     section="User Groups",
     *     filters={
     *         {"name"="page", "dataType"="integer"},
     *         {"name"="limit", "dataType"="integer"},
     *         {"name"="orderBy", "dataType"="array"},
     *         {"name"="filterBy", "dataType"="array"}
     *     }
     * )
     */
    public function listAction()
    {
        $request = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        /* @var $groupManager \Zym\Bundle\GroupBundle\Entity\GroupManager */
        $groupManager = $this->container->get('fos_user.group_manager');

        $groups = $groupManager->findGroups($filterBy, $page, $limit, $orderBy);

        return array(
            'groups' => $groups
        );
    }

    /**
     * @Route(
     *     "/add.{_format}",
     *     name="zym_user_groups_add",
     *     defaults={"_format" = "html"},
     *     methods={"GET","POST"}
     * )
     * @Route(
     *     ".{_format}",
     *     name="zym_user_groups_post_add",
     *     methods={"POST"}
     * )
     * @View()
     * @ApiDoc(
     *     description="Add a group",
     *     section="User Groups",
     *     parameters={
     *         {"name"="zym_user_group[name]", "dataType"="string", "required"=true, "description"="Name", "readonly"=false},
     *         {"name"="zym_user_user[roles]", "dataType"="array", "required"=true, "description"="Roles", "readonly"=false}
     *     }
     * )
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');
        $request         = $this->getRequest();

        // Check for edit access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\UserBundle\Entity\Group'))) {
            throw new AccessDeniedException();
        }

        $group = new Entity\Group();
        $form = $this->createForm(new Form\GroupType(), $group, array(
            'method' => in_array($request->getMethod(), array('PUT', 'PATCH')) ? $request->getMethod() : 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            /* @var $groupManager \Zym\Bundle\GroupBundle\Entity\GroupManager */
            $groupManager = $this->get('fos_user.group_manager');
            $groupManager->addGroup($group);

            return ViewResponse::createRouteRedirect('zym_user_groups_show', array('id' => $group->getId()), Codes::HTTP_CREATED);
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Show a group
     *
     * @param Entity\Group $group
     * @return array
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_groups_show",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"GET"}
     * )
     *
     * @SecureParam(name="group", permissions="VIEW")
     *
     * @View()
     * @ApiDoc(
     *     description="Returns a group",
     *     section="User Groups"
     * )
     */
    public function showAction(Entity\Group $group)
    {
        return array('group' => $group);
    }

    /**
     * Edit a group
     *
     * @param Entity\Group $group
     * @return array
     *
     * @Route(
     *     "/{id}/edit.{_format}",
     *     name="zym_user_groups_edit",
     *     defaults={"_format" = "html"},
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_groups_put_edit",
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"PUT"}
     * )
     *
     * @SecureParam(name="group", permissions="EDIT")
     *
     * @View()
     * @ApiDoc(
     *     description="Edit a group",
     *     section="User Groups",
     *     parameters={
     *         {"name"="zym_user_group[name]", "dataType"="string", "required"=true, "description"="Name", "readonly"=false},
     *         {"name"="zym_user_user[roles]", "dataType"="array", "required"=true, "description"="Roles", "readonly"=false}
     *     }
     * )
     */
    public function editAction(Entity\Group $group)
    {
        $originalGroup = clone $group;
        $form         = $this->createForm(new Form\GroupType(), $group);

        $request = $this->get('request');
        $form->handleRequest($request);

        if ($form->isValid()) {
            /* @var $groupManager \Zym\Bundle\GroupBundle\Entity\GroupManager */
            $groupManager = $this->get('fos_user.group_manager');
            $groupManager->saveGroup($group);

            return ViewResponse::createRouteRedirect(
                'zym_user_groups_show',
                array('id' => $group->getId()),
                Codes::HTTP_OK
            );
        }

        return array(
            'group' => $originalGroup,
            'form'  => $form->createView()
        );
    }

    /**
     * Delete a group
     *
     * @param Entity\Group $group
     * @return array
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_user_groups_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_groups_delete_delete",
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"DELETE"}
     * )
     * @View()
     *
     * @SecureParam(name="group", permissions="DELETE")
     *
     * @ApiDoc(
     *     description="Delete a group",
     *     section="User Groups"
     * )
     */
    public function deleteAction(Entity\Group $group)
    {
        $form = $this->createForm(new Form\DeleteType(), $group);

        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isValid() || $request->getMethod() == 'DELETE') {
            /* @var $groupManager \FOS\GroupBundle\Entity\GroupManager */
            $groupManager = $this->get('fos_user.group_manager');
            $groupManager->deleteGroup($group);

            return ViewResponse::createRouteRedirect(
                'zym_user_groups',
                array(),
                Codes::HTTP_OK
            );
        }

        return array(
            'group' => $group,
            'form'  => $form
        );
    }
}
