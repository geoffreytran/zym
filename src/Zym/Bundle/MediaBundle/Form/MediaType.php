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
        $builder->appendNormTransformer(new ProviderDataTransformer($this->mediaPool, array(
            'provider' => $options['provider'],
            'context'  => $options['context'],
        )));

        $builder->add('name');
        $builder->add('description');
        $builder->add('authorName');
        $builder->add('copyright');

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