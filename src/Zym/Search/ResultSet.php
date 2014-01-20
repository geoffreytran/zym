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

class ResultSet implements \Iterator, \Countable
{
    private $totalHits;
    private $totalTime;
    private $results = array();
    private $facets;

    public function getTotalHits()
    {
        return $this->totalHits;
    }

    public function setTotalHits($totalHits)
    {
        $this->totalHits = $totalHits;
    }

    public function getTotalTime()
    {
        return $this->totalTime;
    }

    public function setTotalTime($totalTime)
    {
        $this->totalTime = $totalTime;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setResults(array $results)
    {
        $this->results = $results;
    }

    public function getFacets()
    {
        return $this->facets;
    }

    public function setFacets(array $facets)
    {
        $this->facets = $facets;
    }

    public function count()
    {
        return count($this->results);
    }

    public function current()
    {
        return current($this->results);
    }

    public function key()
    {
        return key($this->results);
    }

    public function next()
    {
        return next($this->results);
    }

    public function rewind()
    {
        return rewind($this->results);
    }

    public function valid()
    {
        return isset($this->results[$this->key()]);
    }
}