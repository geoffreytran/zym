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

namespace Zym\Bundle\MenuBundle\Form\MenuItem;

use Zym\Bundle\MenuBundle\Form\MenuItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SectionMenuItemType
 *
 * @package Zym\Bundle\MenuBundle\Form\MenuItem
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class SectionMenuItemType extends MenuItemType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('uri', 'text', array(
        ));
    }
}