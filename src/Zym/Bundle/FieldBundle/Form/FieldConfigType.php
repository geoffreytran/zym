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

namespace Zym\Bundle\FieldBundle\Form;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FieldConfigType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fieldType', 'entity', array(
                    'label'         => 'Type',
                    'required'      => true,
                    'class'         => 'ZymFieldBundle:FieldType',
                    'property'      => 'machineName',
                    'query_builder' => function(ObjectRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('ft');
                        $qb->where('ft.machineName = :machineName');
                
                        $qb->setParameter('machineName', $options['data']->getFieldType());
                        return $qb;
                    }
                ))
                ->add('label', 'text')
                ->add('description', 'textarea')
                ->add('weight', 'integer')
                ->add('required', 'checkbox');

    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'field_configs';
    }
}