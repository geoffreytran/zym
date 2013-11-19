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

use Symfony\Component\HttpFoundation\Request;
use Zym\Bundle\UserBundle\Form;
use Zym\Bundle\UserBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use FOS\RestBundle\Controller\Annotations\View;


/**
 * Security Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @View()
     */
    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * @Route(
     *     "/add.{_format}",
     *     name="zym_user_users_add",
     *     defaults={
     *         "_format" = "html"
     *     }
     * )
     * @Route(
     *     "",
     *     name="zym_user_users_post_add",
     *     methods={"POST"}
     * )
     * @View()
     */
    public function addAction()
    {
        $securityContext = $this->get('security.context');

        // check for edit access
        if (!$securityContext->isGranted('CREATE', new ObjectIdentity('class', 'Zym\Bundle\UserBundle\Entity\User'))) {
            //   throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $user = new Entity\User();
        $form = $this->createForm(new Form\UserType(), $user, array('csrf_protection' => false));

        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isValid()) {
            /* @var $userManager \Zym\Bundle\UserBundle\Entity\UserManager */
            $userManager = $this->get('fos_user.user_manager');

            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $userManager->addUser($user);

            return $this->redirect($this->generateUrl('zym_user_users'));
        }

        return array(
            'form' => $form
        );
    }

}
