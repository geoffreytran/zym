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

use Zym\Bundle\UserBundle\Form,
    Zym\Bundle\UserBundle\Entity,
    Zym\Bundle\CMSBundle\Entity\AuditLog,
    Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Security\Acl\Domain\ObjectIdentity,
    JMS\SecurityExtraBundle\Annotation\Secure,
    JMS\SecurityExtraBundle\Annotation\SecureParam,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Users Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class UsersController extends Controller
{
    /**
     * @Route(
     *     ".{_format}",
     *     name="zym_user_users",
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

        $userManager = $this->container->get('fos_user.user_manager');
        /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */

        $users = $userManager->findUsers($filterBy, $page, $limit, $orderBy);

        return array(
            'users' => $users
        );
    }

    /**
     * @Route("/create", name="zym_user_users_create")
     * @Template()
     */
    public function createAction()
    {
        $securityContext = $this->get('security.context');

        // check for edit access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\UserBundle\Entity\User'))) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $user = new Entity\User();
        $form = $this->createForm(new Form\UserType(), $user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */

                $user->setConfirmationToken(null);
                $user->setEnabled(true);
                $userManager->addUser($user);

                return $this->redirect($this->generateUrl('zym_user_users'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * Show a user
     *
     * @param Entity\User $user
     *
     * @Route(
     *     "/{id}.{_format}",
     *     name="zym_user_users_show",
     *     defaults = {
     *         "_format" = "html"
     *     },
     *     requirements = {
     *         "id" = "\d+",
     *         "_format" = "html|json"
     *     }
     * )
     * @ParamConverter("user", class="ZymUserBundle:User")
     * @Template()
     *
     * @SecureParam(name="user", permissions="VIEW")
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
     * @Route(
     *     "/{id}/edit",
     *     name="zym_user_users_edit",
     *     requirements = {
     *         "id" = "\d+"
     *     }
     * )
     * @ParamConverter("user", class="ZymUserBundle:User")
     * @Template()
     *
     * @SecureParam(name="user", permissions="EDIT")
     */
    public function editAction(Entity\User $user)
    {
        $originalUser = clone $user;
        $form         = $this->createForm(new Form\EditUserType(), $user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */

                $userManager->saveUser($user);

                return $this->redirect($this->generateUrl(
                    'zym_user_users_show',
                    array('id' => $user->getId())
                ));
            }
        }

        return array(
            'user' => $originalUser,
            'form' => $form->createView()
        );
    }

    /**
     * Delete a user
     *
     * @param Entity\User $user
     *
     * @Route(
     *     "/{id}/delete.{_format}",
     *     name="zym_user_users_delete",
     *     defaults={ "_format" = "html" },
     *     requirements = {
     *         "id" = "\d+"
     *     }
     * )
     * @Template()
     *
     * @SecureParam(name="user", permissions="DELETE")
     */
    public function deleteAction(Entity\User $user)
    {
        $form = $this->createForm(new Form\DeleteType(), $user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $userManager = $this->get('fos_user.user_manager');
                /* @var $userManager \FOS\UserBundle\Entity\UserManager */
                $userManager->deleteUser($user);

                return $this->redirect($this->generateUrl(
                    'zym_user_users',
                    array('id' => $user->getId())
                ));
            }
        }

        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }
}
