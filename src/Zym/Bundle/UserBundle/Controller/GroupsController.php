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
     *     requirements={
     *         "_format" = "html|json"
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
        $filterBy = $request->query->get('filterBy');

        $groupManager = $this->container->get('fos_user.group_manager');
        /* @var $groupManager \Zym\Bundle\GroupBundle\Entity\GroupManager */

        $groups = $groupManager->findGroups($filterBy, $page, $limit, $orderBy);

        return array(
            'groups' => $groups
        );
    }

    /**
     * @Route("/add", name="zym_user_groups_add")
     * @Template()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for edit access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\UserBundle\Entity\Group'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $group = new Entity\Group();
        $form = $this->createForm(new Form\GroupType(), $group);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $groupManager = $this->get('fos_user.group_manager');
                /* @var $groupManager \Zym\Bundle\GroupBundle\Entity\GroupManager */

                $groupManager->addGroup($group);

                return $this->redirect($this->generateUrl('zym_user_groups'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Show a group
     *
     * @param Entity\Group $group
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_groups_show",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+",
     *         "_format" = "html|json"
     *     }
     * )
     * @Template()
     *
     * SecureParam(name="group", permissions="VIEW")
     */
    public function showAction(Entity\Group $group)
    {
        return array('group' => $group);
    }

    /**
     * Edit a group
     *
     * @param Entity\Group $group
     *
     * @Route(
     *     "/{id}/edit",
     *     name="zym_user_groups_edit",
     *     requirements = {
     *         "id" = "\d+"
     *     }
     * )
     * @Template()
     *
     * @SecureParam(name="group", permissions="EDIT")
     */
    public function editAction(Entity\Group $group)
    {
        $originalGroup = clone $group;
        $form         = $this->createForm(new Form\GroupType(), $group);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $groupManager = $this->get('fos_user.group_manager');
                /* @var $groupManager \Zym\Bundle\GroupBundle\Entity\GroupManager */

                $groupManager->saveGroup($group);

                return $this->redirect($this->generateUrl(
                    'zym_user_groups_show',
                    array('id' => $group->getId())
                ));
            }
        }

        return array(
            'group' => $originalGroup,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a group
     *
     * @param Entity\Group $group
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_user_groups_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = "\d+"
     *     }
     * )
     * @Template()
     *
     * @SecureParam(name="group", permissions="DELETE")
     */
    public function deleteAction(Entity\Group $group)
    {
        $form = $this->createForm(new Form\DeleteType(), $group);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $groupManager = $this->get('fos_user.group_manager');
                /* @var $groupManager \FOS\GroupBundle\Entity\GroupManager */
                $groupManager->deleteGroup($group);

                return $this->redirect($this->generateUrl(
                    'zym_user_groups',
                    array('id' => $group->getId())
                ));
            }
        }

        return array(
            'group' => $group,
            'form' => $form->createView()
        );
    }
}
