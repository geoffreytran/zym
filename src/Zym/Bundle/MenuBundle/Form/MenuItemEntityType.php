<?php

namespace Zym\Bundle\MenuBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuItemEntityType extends EntityType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $registry = $this->registry;

        $choiceList = function (Options $options) use ($registry) {
            if ($options['em'] instanceof \Doctrine\Common\Persistence\ObjectManager) {
                $manager = $options['em'];
            } else {
                $manager = $registry->getManager($options['em']);
            }

            return new MenuChoiceList(
                $manager,
                $options['class'],
                $options['property'],
                $options['loader'],
                $options['choices'],
                $options['group_by']
            );
        };

        $resolver->replaceDefaults(array(
            'choice_list' => $choiceList,
        ));
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'menu_item_entity';
    }
}