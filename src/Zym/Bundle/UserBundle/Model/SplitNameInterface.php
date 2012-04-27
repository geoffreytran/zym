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
 * Split name interface
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
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