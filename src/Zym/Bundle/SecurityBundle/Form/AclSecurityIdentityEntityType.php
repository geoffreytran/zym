<?php
namespace Zym\Bundle\SecurityBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AclSecurityIdentityEntityType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            // Symfony <2.3
            if (defined('Symfony\Component\Form\FormEvents::BIND_NORM_DATA')) {
                $eventName = FormEvents::BIND_NORM_DATA;
            } else {
                $eventName = FormEvents::BIND;
            }
            
            $builder->addEventListener($eventName, function(Event $event) use ($options){
                $event->stopPropagation();
            }, 4);
        }

        $builder->resetViewTransformers();

        if ($options['multiple']) {
            $builder->addViewTransformer(new AclSecurityIdentityToArrayTransformer($options['choice_list']));
        } else {
            $builder->addViewTransformer(new AclSecurityIdentityToStringTransformer($options['choice_list']));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class'             => 'ZymSecurityBundle:AclSecurityIdentity',
            'property'          => 'identifier',
            'multiple'          => true,
            //'data_class'        => 'Zym\\Bundle\\SecurityBundle\\Entity\\AclSecurityIdentity',
            'query_builder'     => function(ObjectRepository $er) {
                return $er->createQueryBuilder('r')
                          ->where('r.username = 0');
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'acl_security_identity_entity';
    }
}