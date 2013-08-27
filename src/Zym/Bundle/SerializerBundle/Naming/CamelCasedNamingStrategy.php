<?php

namespace Zym\Bundle\SerializerBundle\Naming;

use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Metadata\PropertyMetadata;

/**
 * Generic naming strategy which translates a camel-cased property name.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class CamelCasedNamingStrategy implements PropertyNamingStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function translateName(PropertyMetadata $property)
    {
        $name = preg_replace('/[A-Z]/', '\\0', $property->name);
        return lcfirst($name);
    }
}