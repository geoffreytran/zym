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

use Zym\Search\Document\Field;

class Document implements DocumentInterface
{
    /**
     * Id
     *
     * @var string
     */
    protected $id;

    /**
     * Fields
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Boost
     *
     * @var float
     */
    protected $boost;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getField($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        foreach ($this->fields as $field) {
            /* @var $field Field */
            if ($field->getName() == $name) {
                $this->fields[$name] = $field;
                return $field;
            }
        }

        throw new \OutOfBoundsException(sprintf('Field name "%s" is not a valid field', $name));
    }

    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    public function hasField($name)
    {
        if (isset($this->fields[$name])) {
            return true;
        }

        foreach ($this->fields as $field) {
            /* @var $field Field */
            if ($field->getName() == $name) {
                $this->fields[$name] = $field;
                return true;
            }
        }

        return false;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function getBoost()
    {
        return $this->boost;
    }

    public function setBoost($boost)
    {
        $this->boost = $boost;
    }
}