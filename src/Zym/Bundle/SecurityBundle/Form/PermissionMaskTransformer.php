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

namespace Zym\Bundle\SecurityBundle\Form;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Transforms between a given value and a string.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
class PermissionMaskTransformer implements DataTransformerInterface
{
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
        $values = array();
        
        $reflection = new \ReflectionClass('Symfony\Component\Security\Acl\Permission\MaskBuilder');
        foreach ($reflection->getConstants() as $name => $cMask) {
            $cName = substr($name, 5);
            
            if (0 === strpos($name, 'MASK_')) {
                $maskValue = constant('Symfony\Component\Security\Acl\Permission\MaskBuilder::' . $name);
                
                if (($value & $maskValue) == $maskValue) {
                    $values[strtolower($cName)] = true;
                }
            }
        }

        return $values;
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
        $maskBuilder = new MaskBuilder();
        foreach ($value as $mask => $maskValue) {
            if ($maskValue) {
                $maskBuilder->add($mask);
            }
        }

        return $maskBuilder->get();
    }
}