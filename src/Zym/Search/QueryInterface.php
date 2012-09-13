<?php

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