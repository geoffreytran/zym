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

namespace Zym\Bundle\NodeBundle\Form;

use Zym\Bundle\NodeBundle\Entity;
use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class NodeTypeType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'text')
                ->add('name', 'text')
                ->add('description', 'textarea');
        
        // /* @var $node Entity\NodeType */
        //         $nodeType = $builder->getData();
        // 
        //         if (!$nodeType instanceof Entity\NodeType) {
        //             throw new \InvalidArgumentException(
        //                 'Data for form must be of type Zym\Bundle\NodeBundle\Entity\NodeType'
        //             );
        //         }
        // 
        // 
        //         foreach ($node->getFieldConfigs() as $fieldConfig) {
        //             /* @var $field FieldConfig */
        //             $machineName = $fieldConfig->getFieldType()->getMachineName();
        //             $builder->add($machineName, $fieldConfig, array('property_path' => 'fields.' . $machineName . '.data'));
        //         }
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'node_type';
    }
}