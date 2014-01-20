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

namespace Zym\Bundle\MailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MailConfigType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('override', 'checkbox', array(
            'required' => false
        ));

        $builder->add('transport', 'choice', array(
            'choices' => array(
                'smtp'     => 'SMTP',
                'mail'     => 'Mail',
                'sendmail' => 'Sendmail',
                'gmail'    => 'GMail'
            )
        ));

        $builder->add('host', 'text', array(
            'required' => false
        ));

        $builder->add('port', 'text', array(
            'required' => false
        ));

        $builder->add('username', 'text', array(
            'required' => false
        ));

        $builder->add('password', 'password', array(
            'required' => false
        ));

        $builder->add('encryption', 'choice', array(
            'choices' => array(
                null  => 'None',
                'tls' => 'TLS',
                'ssl' => 'SSL'
            ),
            'expanded' => true,
            'required' => false
        ));

        $builder->add('authMode', 'choice', array(
            'choices' => array(
                'plain'    => 'Plain',
                'login'    => 'Login',
                'cram-md5' => 'CRAM MD5'
            ),
            'expanded' => true,
            'required' => false,
            'label' => 'Auth Mode'
        ));

    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_mail_mail_config';
    }
}