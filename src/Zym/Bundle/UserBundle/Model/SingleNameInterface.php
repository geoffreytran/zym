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
 * Interface SingleNameInterface
 *
 * For users with the ability to get their name in a single field.
 *
 * @package Zym\Bundle\UserBundle\Model
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
interface SingleNameInterface extends FullNameInterface
{
	/**
	 * Get the user's name
	 *
	 * @return string
	 */
	public function getName();
	
	/**
	 * Set the user's name
	 *
	 * @param string $name
	 * @return SingleNameInterface
	 */
	public function setName($name);
}