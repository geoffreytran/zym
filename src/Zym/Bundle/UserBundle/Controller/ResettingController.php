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

use FOS\RestBundle\View\View as ViewResponse;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;

use FOS\UserBundle\Model\UserManager;
use Symfony\Component\Translation\TranslatorInterface;
use Zym\Bundle\UserBundle\Form;
use Zym\Bundle\UserBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class ResettingController
 *
 * @package Zym\Bundle\UserBundle\Controller
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class ResettingController extends Controller
{
    const SESSION_EMAIL = 'fos_user_send_resetting_email/email';

    /**
     * Request reset user password: show form
     *
     * @Route(
     *     ".{_format}",
     *     name="zym_user_resetting_request",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET", "POST"},
     *     options={"expose" = true}
     * )
     *  @Route(
     *     ".{_format}",
     *     name="fos_user_resetting_request",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET", "POST"},
     *     options={"expose" = true}
     * )
     * @View()
     * @ApiDoc(
     *     description="Request reset user password.",
     *     section="User Resetting",
     *     parameters={
     *         {"name"="username", "dataType"="string", "required"=true, "description"="Username or Email address", "readonly"=false}
     *     }
     * )
     */
    public function requestAction(Request $request)
    {
        $username = $request->request->get('username');

        if ($request->getMethod() == 'POST') {
            /** @var UserManager */
            $userManager = $this->container->get('fos_user.user_manager');

            /** @var $user UserInterface */
            $user = $userManager->findUserByUsernameOrEmail($username);

            /** @var TranslatorInterface */
            $translator = $this->get('translator');

            // Invalid username or email
            if (null === $user) {
                return ViewResponse::create(array(
                    'invalid_username' => $username,
                    'code'    => 412,
                    'message' => $translator->trans('resetting.request.invalid_username', array('%username%' => $username), 'FOSUserBundle')
                ), 412);
            }

            // User already requested password
            if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
                return ViewResponse::create(array(
                    'code'    => 409,
                    'message' => $translator->trans('resetting.password_already_requested', array('%username%' => $username), 'FOSUserBundle')
                ), 409);
            }

            // Generate confirmation token
            if (null === $user->getConfirmationToken()) {
                /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }


            $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
            $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);

            $user->setPasswordRequestedAt(new \DateTime());
            $userManager->updateUser($user);
            return ViewResponse::createRouteRedirect('zym_user_resetting_check_email', array('_format' => $request->getRequestFormat()));
        }

        return array();
    }

    /**
     * Request reset user password: submit form and send email
     *
     * @Route(
     *     "send-email.{_format}",
     *     name="zym_user_resetting_send_email",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"POST"},
     *     options={"expose" = true}
     * )
     * @View()
     * @ApiDoc(
     *     description="Request reset user password: submit form and send email",
     *     section="User Resetting",
     *     parameters={
     *         {"name"="username", "dataType"="string", "required"=true, "description"="Username or Email address", "readonly"=false}
     *     }
     * )
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->request->get('username');

        /** @var $user UserInterface */
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
            return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:request.html.' . $this->getEngine(), array('invalid_username' => $username));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:passwordAlreadyRequested.html.' . $this->getEngine());
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_check_email'));
    }

    /**
     * Tell the user to check his email provider
     *
     *  @Route(
     *     "check-email.{_format}",
     *     name="zym_user_resetting_check_email",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET"},
     *     options={"expose" = true}
     * )
     * @Route(
     *     "check-email.{_format}",
     *     name="fos_user_resetting_check_email",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET"},
     *     options={"expose" = true}
     * )
     * @View()
     */
    public function checkEmailAction()
    {
        $session = $this->container->get('session');
        $email   = $session->get(static::SESSION_EMAIL);
        //$session->remove(static::SESSION_EMAIL);

        if (empty($email)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        return array(
            'email' => $email
        );
    }

    /**
     * Reset user password
     *
     * @Route(
     *     "reset/{token}.{_format}",
     *     name="zym_user_resetting_reset",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET", "POST"},
     *     options={"expose" = true}
     * )
     * @Route(
     *     "reset/{token}.{_format}",
     *     name="fos_user_resetting_reset",
     *     defaults={
     *         "_format" = "html"
     *     },
     *     methods={"GET", "POST"},
     *     options={"expose" = true}
     * )
     * @View()
     * @ApiDoc(
     *     description="Reset user password.",
     *     section="User Resetting",
     *     parameters={
     *         {"name"="fos_user_resetting_form[plainPassword][first]", "dataType"="string", "required"=true, "description"="Password", "readonly"=false}
     *     }
     * )
     */
    public function resetAction(Request $request, $token)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.resetting.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url      = $this->container->get('router')->generate('fos_user_security_login');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::RESETTING_RESET_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return array(
            'token' => $token,
            'form'  => $form
        );
    }

    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
    }
}
