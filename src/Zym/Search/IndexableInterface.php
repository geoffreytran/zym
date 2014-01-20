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