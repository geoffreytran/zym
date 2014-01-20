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

namespace Zym\Dbal\Type;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

/**
 * Class UTCDateTimeType
 *
 * @package Zym\Dbal\Type
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
class UTCDateTimeType extends DateTimeType
{
    static private $utc = null;

    /**
     * Convert to database value.
     *
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (is_null(self::$utc)) {
            self::$utc = new \DateTimeZone('UTC');
        }

        $cloneDate = clone $value;
        $cloneDate->setTimeZone(self::$utc);

        return $cloneDate->format($platform->getDateTimeFormatString());
    }

    /**
     * Convert to PHP value.
     *
     * @param                  $value
     * @param AbstractPlatform $platform
     *
     * @return \DateTime|mixed|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (is_null(self::$utc)) {
            self::$utc = new \DateTimeZone('UTC');
        }

        $val = \DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, self::$utc);

        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $val;
    }
}