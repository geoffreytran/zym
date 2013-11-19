<?php
/**
 * RAPP
 *
 * LICENSE
 *
 * This file is intellectual property of RAPP and may not
 * be used without permission.
 *
 * @category  RAPP
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
namespace Zym\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * User Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class EditUserType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->get('plainPassword')->setRequired(false);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_user_user';
    }
}