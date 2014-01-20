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

namespace Zym\Bundle\UserBundle\Model;

/**
 * Interface TimeZoneInterface
 *
 * For users that implement the ability to get/set their timezone.
 *
 * @package Zym\Bundle\UserBundle\Model
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
interface TimeZoneInterface
{
    /**
     * Get a DateTimeZone instance for the user
     *
     * Returns default timezone if not set.
     *
     * @return \DateTimeZone
     */
    public function getDateTimeZone();

    /**
     * Get the time zone
     *
     * @return string
     */
    public function getTimeZone();
    /**
     * Set the time zone
     *
     * @param string $timeZone
     */
    public function setTimeZone($timeZone);
}