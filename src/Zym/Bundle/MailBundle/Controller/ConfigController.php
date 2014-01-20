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
            $form->bind($request);

            if ($form->isValid()) {
                $mailConfigManager->saveMailConfig($mailConfig);

                $translator = $this->get('translator');

                $this->get('session')
                     ->getFlashBag()->add('success', $translator->trans('Changes saved!'));


                return $this->redirect($this->generateUrl('zym_mail_config_edit'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }
}
