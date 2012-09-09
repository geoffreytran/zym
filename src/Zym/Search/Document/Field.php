<?php

namespace Zym\Search\Document;

class Field
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_LONG = 'long';
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_BINARY = 'binary';
    const TYPE_DATE = 'date';

    /**
     * Name
     *
     * @var string
     */
    protected $name;

    /**
     * Value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Type
     *
     * @var string
     */
    protected $type;

    /**
     * Whether this field is stored
     *
     * @var boolean
     */
    protected $stored;

    /**
     * Whether this field is indexed
     *
     * @var boolean
     */
    protected $indexed;

    /**
     * Whether to store this field as a term vector
     *
     * @var boolean
     */
    protected $storeTermVector;

    /**
     * Value to boost this field
     *
     * @var float
     */
    protected $boost;

    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
    
    public function isStored()
    {
        return $this->stored;
    }
    
    public function setStored($stored)
    {
        $this->stored = $stored;
    }
    
    public function isIndexed()
    {
        return $this->indexed;
    }
    
    public function setIndexed($indexed)
    {
        $this->indexed = $indexed;
    }
    
    public function getStoreTermVector()
    {
        return $this->storeTermVector;
    }
    
    public function setStoreTermVector($storeTermVector)
    {
        $this->storeTermVector = $storeTermVector;
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