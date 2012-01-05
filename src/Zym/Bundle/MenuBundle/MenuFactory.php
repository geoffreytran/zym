<?php

namespace Zym\Bundle\MenuBundle;

use Zym\Bundle\MenuBundle\Entity\MenuItem;
use Knp\Bundle\MenuBundle\FactoryInterface;
use Knp\Bundle\MenuBundle\ItemInterface;
use Knp\Bundle\MenuBundle\NodeInterface;

class MenuFactory implements FactoryInterface
{
    /**
     * Creates a menu item
     *
     * @param string $name
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createItem($name, array $options = array())
    {
        $item = new MenuItem($name, $this);

        $options = array_merge(
            array(
                'uri' => null,
                'label' => null,
                'attributes' => array(),
                'linkAttributes' => array(),
                'childrenAttributes' => array(),
                'labelAttributes' => array(),
                'display' => true,
                'displayChildren' => true,
            ),
            $options
        );

        $item
            ->setUri($options['uri'])
            ->setLabel($options['label'])
            ->setAttributes($options['attributes'])
            ->setLinkAttributes($options['linkAttributes'])
            ->setChildrenAttributes($options['childrenAttributes'])
            ->setLabelAttributes($options['labelAttributes'])
            ->setDisplay($options['display'])
            ->setDisplayChildren($options['displayChildren'])
        ;

        return $item;    }

    /**
     * Create a menu item from a NodeInterface
     *
     * @param \Knp\Menu\NodeInterface $node
     * @return \Knp\Menu\ItemInterface
     */
    public function createFromNode(NodeInterface $node)
    {
        
    }

    /**
     * Creates a new menu item (and tree if $data['children'] is set).
     *
     * The source is an array of data that should match the output from MenuItem->toArray().
     *
     * @param  array $data The array of data to use as a source for the menu tree
     * @return \Knp\Menu\ItemInterface
     */
    public function createFromArray(array $data)
    {
        
    }
}