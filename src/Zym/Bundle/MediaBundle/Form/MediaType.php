<?php

namespace Zym\Bundle\MediaBundle\Form;

use Zym\Bundle\MediaBundle\MediaPool;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Zym\Bundle\MediaBundle\Form\ProviderDataTransformer;

class MediaType extends AbstractType
{
    protected $mediaPool;

    protected $class;

    /**
     * @param MediaPool   $pool
     * @param string $class
     */
    public function __construct(MediaPool $mediaPool, $class)
    {
        $this->mediaPool  = $mediaPool;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ProviderDataTransformer($this->mediaPool, array(
            'provider' => $options['provider'],
            'context'  => $options['context'],
        )));

        $builder->add('name', 'text', array(
            'help_block' => 'Name to call this item.',
            'attr' => array(
                'class' => 'input-block-level'
            )
        ));

        $builder->add('description', 'textarea', array(
            'help_block' => 'A short description of the item.',
            'attr' => array(
                'class' => 'input-block-level'
            ),
            'required' => false
        ));

        $builder->add('authorName', 'text', array(
            'attr' => array(
                'class' => 'input-block-level'
            ),
            'required' => false
        ));

        $builder->add('copyright', 'text', array(
            'attr' => array(
                'class' => 'input-block-level'
            ),
            'required' => false
        ));

        $this->mediaPool->getProvider($options['provider'])->buildMediaType($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'provider'   => null,
            'context'    => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zym_media_type';
    }
}