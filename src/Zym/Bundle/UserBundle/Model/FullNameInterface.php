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
 * Interface FullNameInterface
 *
 * For users that support the ability to get their full name.
 *
 * @package Zym\Bundle\UserBundle\Model
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
interface FullNameInterface
{
	/**
	 * Get the user's full name
	 *
	 * @return string
	 */
	public function getFullName();
}