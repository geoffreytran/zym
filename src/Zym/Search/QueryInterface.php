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

namespace Zym\Search;

interface QueryInterface
{
    public function getQuery();
    public function setQuery($query);

    public function getLimit();
    public function setLimit($limit);

    public function getOffset();
    public function setOffset($offset);

    public function getIndexManager();
    public function setIndexManager(IndexManagerInterface $indexManager);
}