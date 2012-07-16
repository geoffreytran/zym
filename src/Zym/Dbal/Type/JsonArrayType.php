<?php

namespace Zym\Dbal\Type;

use Doctrine\DBAL\Types;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class JsonArrayType extends Types\JsonArrayType
{

    /**
     * Convert to the PHP value
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return array();
        }
        
        $value = (is_resource($value)) ? stream_get_contents($value) : $value;
    
        $return = json_decode($value, true);
        
        // Determine if this field has PHP serialized data instead
        if (json_last_error() === JSON_ERROR_SYNTAX && $this->isSerialized($value)) {
            return unserialize($value);
        }
        
        return $return;
    }

    /**
     * Convert to the database stored value
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        
        return json_encode($value);
    }
    
    /**
     * Is value serialized
     * 
     * @param mixed $data
     * @return bool
     */
    private function isSerialized($data) 
    {
        // if it isn't a string, it isn't serialized
        if (!is_string($data)) {
            return false;
        }
        
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        
        if (!preg_match( '/^([adObis]):/', $data, $badions)) {
            return false;
        }
        
        switch ($badions[1]) {
            case 'a' :
            case 'O' :
            case 's' :
                if (preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
                    return true;
                break;
                
            case 'b' :
            case 'i' :
            case 'd' :
                if (preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data))
                    return true;
                break;
        }
        
        return false;
    } 
}