<?php
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