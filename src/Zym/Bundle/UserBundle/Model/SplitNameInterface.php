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
 * Interface SplitNameInterface
 *
 * For users with their names stored in separate fields such as firstName/lastName.
 *
 * @package Zym\Bundle\UserBundle\Model
 * @author  Geoffrey Tran <geoffrey.tran@gmail.com>
 */
interface SplitNameInterface extends FullNameInterface
{
	/**
	 * Get the user's first name
	 *
	 * @return string
	 */
	public function getFirstName();

	/**
	 * Set the user's first name
	 *
	 * @param string $firstName
	 * @return SingleNameInterface
	 */
	public function setFirstName($firstName);
	
	/**
	 * Get the user's middle name
	 *
	 * @return string
	 */
	public function getMiddleName();
	
	/**
	 * Set the user's first name
	 *
	 * @param string $middleName
	 * @return SingleNameInterface
	 */
	public function setMiddleName($middleName);
	
	/**
	 * Get the user's last name
	 *
	 * @return string
	 */
	public function getLastName();
	
	/**
	 * Set the user's first name
	 *
	 * @param string $lastName
	 * @return SingleNameInterface
	 */
	public function setLastName($lastName);
}