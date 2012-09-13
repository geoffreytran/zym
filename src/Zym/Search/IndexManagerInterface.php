<?php

namespace Zym\Search;

interface IndexManagerInterface
{

    public function search($query);
    public function index($indexables, array $options = array());

    public function findDocumentIds($query, array $options = array());

    public function delete(DocumentInterface $document);

    public function deleteById($id);

    public function deleteByIds(array $ids);

}