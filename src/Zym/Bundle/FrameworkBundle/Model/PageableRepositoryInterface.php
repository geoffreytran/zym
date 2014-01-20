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