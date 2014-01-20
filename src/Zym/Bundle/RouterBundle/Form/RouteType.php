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

namespace Zym\Bundle\RouterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');

        $builder->add('path', 'text');

        $builder->add('defaults', new RouteArrayType());

        $builder->add('requirements', new RouteArrayType(), array(
            'required' => false
        ));

        $builder->add('methods', new RouteArrayType(), array(
            'required' => false
        ));

        $builder->add('schemes', new RouteArrayType(), array(
            'required' => false
        ));

        $builder->add('host', 'text', array(
            'required' => false
        ));

        $builder->add('weight', 'integer');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_router_route';
    }
}