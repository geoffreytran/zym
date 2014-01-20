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

namespace Zym\Search\Elastica;

use Zym\Search\Query;
use Zym\Search\QueryInterface;
use Zym\Search\DocumentInterface;
use Zym\Search\IndexManagerInterface;

class IndexManager implements IndexManagerInterface
{
    /**
     * Elastica Index
     *
     * @var \Elastica_Index
     */
    private $index;

    public function __construct(\Elastica_Index $index)
    {
        $this->index = $index;
    }

    protected function getIndex()
    {
        return $this->index;
    }

    public function search($query)
    {
        if (is_string($query)) {
            $queryObj = new Query();
            $queryObj->setQuery($query);

            $query = $queryObj;
        }

        if ($query instanceof QueryInterface) {
            $query->setIndexManager($this);

            // Build the Elastica Query
            $elasticaQuery = new \Elastica_Query();

            // Define a Query. We want a string query.
            $elasticaQueryString = new \Elastica_Query_QueryString();
            $elasticaQueryString->setDefaultOperator('AND');
            $elasticaQueryString->setQuery($query->getQuery());

            $elasticaQuery->setQuery($elasticaQueryString);
            $elasticaQuery->setFrom($query->getOffset());
            $elasticaQuery->setLimit($query->getLimit());

            $index             = $this->getIndex();
            $elasticaResultSet = $index->search($elasticaQuery);
            return $elasticaResultSet;

        }

        throw new \InvalidArgumentException();
    }

    public function index($indexables, array $options = array())
    {}

    public function findDocumentIds($query, array $options = array())
    {}

    public function delete(DocumentInterface $document)
    {}

    public function deleteById($id)
    {}

    public function deleteByIds(array $ids)
    {}
}