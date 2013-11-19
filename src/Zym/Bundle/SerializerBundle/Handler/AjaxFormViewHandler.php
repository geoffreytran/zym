<?php

namespace Zym\Bundle\SerializerBundle\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use Symfony\Component\Form\FormView;

class AjaxFormViewHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type'   => 'Symfony\\Component\\Form\\FormView',
                'method' => 'serializeFormViewToJson',
            ),
        );
    }

    public function serializeFormViewToJson(JsonSerializationVisitor $visitor, FormView $formView, array $type, Context $context)
    {
        $output = array();

        if (!$formView->vars['valid']) {
            if ($formView->vars['errors']) {
                foreach ($formView->vars['errors'] as $error) {
                    $output['global'] = $error->getMessage();
                }
            }

            foreach ($formView->children as $fieldName => $child) {
                if (!$child->vars['valid']) {
                    foreach ($child->vars['errors'] as $error) {
                        $output[$fieldName] = $error->getMessage();
                    }
                }
            }
        }

        return $context->accept($output);
    }
}