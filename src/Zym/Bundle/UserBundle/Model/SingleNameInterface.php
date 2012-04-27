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
 * Single name interface
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
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