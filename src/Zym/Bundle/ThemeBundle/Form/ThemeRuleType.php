<?php
namespace Zym\Bundle\ThemeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ThemeRuleType extends AbstractType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('path', 'text', array(
            'help_block' => 'Regular expression path to match.'
        ));

        $builder->add('host', 'text', array(
            'required' => false,
            'help_block' => 'Regular expression host to match.'
        ));

        $builder->add('theme', 'text', array(

        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'zym_theme_theme_rule';
    }
}