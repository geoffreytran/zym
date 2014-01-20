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

namespace Zym\Bundle\UserBundle\Controller;

use FOS\RestBundle\EventListener\ViewResponseListener;
use FOS\RestBundle\View\View as ViewResponse;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
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
 * Class UsersController
 *
 * @package Zym\Bundle\UserBundle\Controller
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class UsersController extends Controller
{
    /**
     * List all users
     *
     * @Route(
     *     ".{_format}",
     *     name="zym_user_users",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET"}
     * )
     * @View()
     *
     * @ApiDoc(
     *     description="Returns a list users",
     *     section="Users",
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
        $request  = $this->get('request');
        $page     = $request->query->get('page', 1);
        $limit    = $request->query->get('limit', 50);
        $orderBy  = $request->query->get('orderBy');
        $filterBy = $request->query->get('filterBy');

        /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */
        $userManager = $this->container->get('fos_user.user_manager');

        $users = $userManager->findUsers($filterBy, $page, $limit, $orderBy);

        return array(
            'users' => $users
        );
    }

    /**
     * @deprecated
     *
     * @Route("/create", name="zym_user_users_create")
     * @Template()
     */
    public function createAction()
    {
        return $this->forward('ZymUserBundle:Users:add');
    }

    /**
     * Add a user
     *
     * @Route(
     *     "/add.{_format}",
     *     name="zym_user_users_add",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     ".{_format}",
     *     name="zym_user_users_post_add",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"POST"}
     * )
     *
     * @View()
     * @ApiDoc(
     *     description="Add a user",
     *     section="Users",
     *     parameters={
     *         {"name"="zym_user_user[email]", "dataType"="string", "required"=true, "description"="Email address", "readonly"=false},
     *         {"name"="zym_user_user[plainPassword][password]", "dataType"="string", "required"=true, "description"="Password", "readonly"=false},
     *         {"name"="zym_user_user[plainPassword][confirmPassword]", "dataType"="string", "required"=true, "description"="Confirm Password", "readonly"=false}
     *     }
     * )
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // Check for create access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\UserBundle\Entity\User'))) {
            throw new AccessDeniedException();
        }

        $user = new Entity\User();
        $form = $this->createForm(new Form\UserType(), $user);

        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {
            /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */
            $userManager = $this->get('fos_user.user_manager');

            $user->setConfirmationToken(null);

            $userManager->addUser($user);

            return ViewResponse::createRouteRedirect(
                                   'zym_user_users_show',
                                   array(
                                        'id'     => $user->getId(),
                                       '_format' => $request->getRequestFormat()
                                   ),
                                   Codes::HTTP_CREATED
                                )
                                ->setData(array(
                                    'user' => $user
                                ));
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Show a user
     *
     * @param Entity\User $user
     *
     * @return array
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_users_show",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"GET"}
     * )
     * @ParamConverter("user", class="ZymUserBundle:User")
     * @View()
     *
     * @SecureParam(name="user", permissions="VIEW")
     *
     * @ApiDoc(
     *     description="Returns a user",
     *     section="Users"
     * )
     */
    public function showAction(Entity\User $user)
    {
        return array('user' => $user);
    }

    /**
     * Edit a user
     *
     * @param Entity\User $user
     *
     * @return array
     *
     * @Route(
     *     "/{id}/edit.{_format}",
     *     name="zym_user_users_edit",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_users_put_edit",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"PUT"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_users_patch_edit",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"PATCH"}
     * )
     *
     * @ParamConverter("user", class="ZymUserBundle:User")
     * @View()
     *
     * @SecureParam(name="user", permissions="EDIT")
     *
     * @ApiDoc(
     *     description="Edit a user",
     *     section="Users",
     *     parameters={
     *         {"name"="zym_user_user[email]", "dataType"="string", "required"=true, "description"="Email address", "readonly"=false},
     *         {"name"="zym_user_user[plainPassword][password]", "dataType"="string", "required"=true, "description"="Password", "readonly"=false},
     *         {"name"="zym_user_user[plainPassword][confirmPassword]", "dataType"="string", "required"=true, "description"="Confirm Password", "readonly"=false}
     *     }
     * )
     */
    public function editAction(Entity\User $user)
    {
        $request = $this->getRequest();

        $originalUser = clone $user;
        $form         = $this->createForm(new Form\EditUserType(), $user, array(
            'method' => in_array($request->getMethod(), array('PUT', 'PATCH')) ? $request->getMethod() : 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->saveUser($user);

            return ViewResponse::createRouteRedirect(
                                    'zym_user_users_show',
                                    array('id' => $user->getId()),
                                    Codes::HTTP_OK
                                )
                                ->setData(array(
                                    'user' => $user
                                ));
        }

        return array(
            'user' => $originalUser,
            'form' => $form
        );
    }

    /**
     * Delete a user
     *
     * @param Entity\User $user
     *
     * @return array
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_user_users_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"GET", "POST"}
     * )
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_users_delete_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = "\d+"
     *     },
     *     methods={"DELETE"}
     * )
     *
     * @SecureParam(name="user", permissions="DELETE")
     *
     * @View()
     * @ApiDoc(
     *     description="Delete a user",
     *     section="Users"
     * )
     */
    public function deleteAction(Entity\User $user)
    {
        $request = $this->getRequest();

        $form = $this->createForm(new Form\DeleteType(), $user, array(
            'method' => in_array($request->getMethod(), array('DELETE')) ? $request->getMethod() : 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isValid() || $request->getMethod() == 'DELETE') {
            /* @var $userManager Entity\UserManager */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->deleteUser($user);

            return ViewResponse::createRouteRedirect('zym_user_users', array(), Codes::HTTP_OK);
        }

        return array(
            'user' => $user,
            'form' => $form
        );
    }
}
