<?php

namespace Zym\Search;

interface IndexManagerInterface
{

    public function search($query);
    public function index($indexables, $update = true, $searchField = null);
    
    public function getDocumentIds($query, $searchField = null);
    
    public function delete(DocumentInterface $document);
    
    public function deleteById($id);
    
    public function deleteByIds(array $ids);

}