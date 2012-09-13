<?php

namespace Zym\Search;

interface DocumentInterface
{
    public function getId();
    public function setId();
    
    public function addField(Field $field);
    public function hasField($name);
    public function getField($name);

    public function getFields();
    public function setFields(array $fields);

    public function getBoost();
    public function setBoost($boost);
}