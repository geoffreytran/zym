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
namespace Zym\Bundle\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Delete Form
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 RAPP. (http://www.rapp.com/)
 */
class DeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_security_delete';
    }
}