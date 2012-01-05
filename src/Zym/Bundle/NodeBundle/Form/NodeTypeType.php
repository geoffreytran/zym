<?php
namespace Zym\Bundle\NodeBundle\Form;

use Zym\Bundle\NodeBundle\Entity;
use Zym\Bundle\FieldBundle\Entity\FieldConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

class NodeTypeType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('type', 'text')
                ->add('name', 'text')
                ->add('description', 'textarea');
        
        $builder->add('fieldConfigs', 'collection', array(
                    'type' => 'entity',
                    'options' => array(
                        'label'         => 'Field Configs',
                        'required'      => false,
                        'class'         => 'ZymNodeBundle:NodeFieldConfig',
                        'property'      => 'label',
                        'query_builder' => function(EntityRepository $er) use ($options) {
                            $qb = $er->createQueryBuilder('fc');
                            $qb->where('fc.nodeType = :nodeType');

                            $qb->setParameter('nodeType', $options['data']);
                            return $qb;
                        }
                    )
                ));
        
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
     * Get the default options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Zym\Bundle\NodeBundle\Entity\NodeType',
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'node';
    }
}