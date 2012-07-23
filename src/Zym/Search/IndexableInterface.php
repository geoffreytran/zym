<?php

namespace Zym\Search;

interface IndexableInterface 
{
    /**
     * Returns the unique identifier for the search index
     *
     * @return string
     */
    public function getSearchDocumentId();
    
    /**
     * Gets a complete search document used for indexing
     *
     * @return DocumentInterface
     */
    public function getSearchDocument();
    
    /**
     * Whether or not this object should be indexed
     *
     * @return boolean
     */
    public function isSearchIndexable();
}