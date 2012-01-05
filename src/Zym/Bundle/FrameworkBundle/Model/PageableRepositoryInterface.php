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

namespace Zym\Bundle\FrameworkBundle\Model;

use Knp\Component\Pager\Paginator;

/**
 * Pageable Repository Interface
 *
 * @author    Geoffrey Tran
 * @copyright Copyright (c) 2011 Zym. (http://www.zym.com/)
 */
interface PageableRepositoryInterface
{
    /**
     * Get the paginator 
     *
     * @return Paginator
     */
    public function getPaginator();
    /**
     * Set the paginator adapter
     *
     * @param Paginator$paginator
     * @return PageableRepositoryInterface
     */
    public function setPaginator(Paginator $paginator);
}