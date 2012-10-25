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

namespace Zym\Bundle\MailBundle\Controller;

use Zym\Bundle\MailBundle\Form;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

use JMS\SecurityExtraBundle\Annotation\Secure;
use JMS\SecurityExtraBundle\Annotation\SecureParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Mail Config Controller
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
class ConfigController extends Controller
{
    /**
     * @Route("edit", name="zym_mail_config_edit")
     * @Template()
     */
    public function editAction()
    {
        $mailConfigManager = $this->get('zym_mail.mail_config_manager');
        $mailConfig        = $mailConfigManager->loadMailConfig();

        $form              = $this->createForm(new Form\MailConfigType(), $mailConfig);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $mailConfigManager->saveMailConfig($mailConfig);

                $translator = $this->get('translator');

                $this->get('session')
                     ->setFlash($translator->trans('Saved'), 'success');


                return $this->redirect($this->generateUrl('zym_mail_config_edit'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}
