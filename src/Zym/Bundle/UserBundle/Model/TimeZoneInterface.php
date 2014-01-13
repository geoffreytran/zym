<?php
/**
 * Zym
 *
 * LICENSE
 *
 * This file is intellectual property of Zym and may not
 * be used without permission.
 *
 * @category  Zym
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */

namespace Zym\Bundle\UserBundle\Model;

/**
 * Timezone interface
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
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