<?php
namespace Zym\Bundle\SecurityBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\Event;


class AclSecurityIdentityEntityType extends AbstractType
{   
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {      
        if ($options['multiple']) {
            $builder->addEventListener(FormEvents::BIND_NORM_DATA, function(Event $event) use ($options){
                $event->stopPropagation();
            }, 4);
        }

        $builder->resetClientTransformers();
        $builder->appendClientTransformer(new AclSecurityIdentityToArrayTransformer($options['choice_list']));        
    }
    
    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'class'             => 'ZymSecurityBundle:AclSecurityIdentity',
            'property'          => 'identifier',
            'multiple'          => true,
            'data_class'        => 'Zym\\Bundle\\SecurityBundle\\Entity\\AclSecurityIdentity',
            'query_builder'     => function(EntityRepository $er) {
                return $er->createQueryBuilder('r')
                          ->where('r.username = 0');
            }
        );

        $options = array_replace($defaultOptions, $options);

        return $options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zym_security_security_identity_entity';
    }
}