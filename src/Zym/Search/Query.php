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

class Query implements QueryInterface
{
    private $query;
    private $limit;
    private $offset;

    /**
     * Index Manager
     *
     * @var IndexManagerInterface
     */
    private $indexManager;

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function getIndexManager()
    {
        return $this->indexManager;
    }

    public function setIndexManager(IndexManagerInterface $indexManager)
    {
        $this->indexManager = $indexManager;
    }
}