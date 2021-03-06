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

namespace Zym\Bundle\FieldBundle\Form;

use Zym\Bundle\FieldBundle\FieldInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

/**
 * Transforms between a given value and a string.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class ValueToTypeTransformer implements DataTransformerInterface
{
    private $field;
    
    /**
     * Transforms a value into a string.
     *
     * @param  mixed  $value   Mixed value.
     *
     * @return string          String value.
     *
     * @throws UnexpectedTypeException if the given value is not a string or number
     */
    public function transform($value)
    { 
        if ($value instanceof FieldInterface) {
            $this->field = $value;
            return $value->getData();
        }
        
        return $value;
    }

    /**
     * Transforms a value into a string.
     *
     * @param  string $value  String value.
     *
     * @return string         String value.
     *
     * @throws UnexpectedTypeException if the given value is not a string
     */
    public function reverseTransform($value)
    {
        
        return $this->field->setData($value);
    }
}