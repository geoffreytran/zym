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