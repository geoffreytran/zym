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

namespace Zym\Bundle\FieldBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;
use Closure;
use ArrayIterator;

class FieldCollection implements Collection
{    
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    protected $fields;
    
    /**
     * Field Configs
     *
     * @var array
     */
    protected $fieldConfigs;
    
    /**
     * Entity Manager
     *
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * Entity
     *
     * @var object
     */
    protected $entity;
    
    /**
     * Whether this collection has been initialized
     *
     * @var boolean
     */
    protected $initialized = false;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $fields
     */
    public function __construct(FieldableInterface $entity, EntityManager $entityManager = null)
    {
        $this->entityManager = $entityManager;
        $this->entity        = $entity;        
    }
    
    /**
     * Initialize the collection
     *
     */
    protected function initialize()
    {
        $entity = $this->entity;
        $em     = $this->entityManager;

        $fields       = array();
        $fieldMap     = array();        
        $fieldConfigs = $entity->getFieldConfigs();
        
        if ($em !== null) { 
            $qb = $em->createQueryBuilder();
            $qb->select('f')
               ->from('Zym\Bundle\FieldBundle\Entity\Field', 'f')
               ->where('f.entityType  = :entityType')
               ->andWhere('f.entityId = :entityId');
           
            $qb->setParameters(array(
                'entityType' => get_class($entity),
                'entityId'   => $entity->getId()
            ));
        
            $fields = $qb->getQuery()->getResult();
        }
        
        // Separate fields into field map
        foreach ($fields as $field) {
            /** @var $field FieldInterface */
            
            $fieldType   = $field->getFieldConfig()
                                 ->getFieldType();
            $machineName = $fieldType->getMachineName();
            $valueCount  = $fieldType->getValueCount();
                                
            if ($valueCount != 1) { 
                if (!isset($fieldMap[$machineName])) {
                    $fieldMap[$machineName] = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
                }
            
                $fieldMap[$machineName][] = $field;
            } else {
                $fieldMap[$machineName] = $field;
            }
        }
        
        // Ensure we add the fields when they don't exist to match
        // the field configs
        foreach ($fieldConfigs as $fieldConfig) {
            /** @var $fieldConfig FieldConfigInterface */
            
            $fieldType   = $fieldConfig->getFieldType();
            $machineName = $fieldType->getMachineName();
            $valueCount  = $fieldType->getValueCount();
            $fieldClass  = $fieldType->getType();

            if (!isset($fieldMap[$machineName])) {
                
                if ($valueCount != 1) {
                    $fieldMap[$machineName] = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);                 
                } else {
                    $field = new $fieldClass();
                    $field->setEntity($entity);
                    $field->setFieldConfig($fieldConfig);
                    
                    $fieldMap[$machineName] = $field;
                }
            }  
            
            $currentValueCount = count($fieldMap[$machineName]) ;
            if ($currentValueCount < $valueCount) {
                
                $addCount = $valueCount - $currentValueCount;
                for ($i=0; $i < $addCount; $i++) {
                    $field = new $fieldClass();
                    $field->setEntity($entity);
                    $field->setFieldConfig($fieldConfig);
                    
                    $fieldMap[$machineName][] = $field;    
                }  
            }
        }
        
        $this->fields = $fieldMap;
        $this->initialized = true;
    }

    /**
     * Gets the PHP array representation of this collection.
     *
     * @return array The PHP array representation of this collection.
     */
    public function toArray()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
        
        return $this->fields;
    }

    /**
     * Sets the internal iterator to the first element in the collection and
     * returns this element.
     *
     * @return mixed
     */
    public function first()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
        
        return reset($this->fields);
    }

    /**
     * Sets the internal iterator to the last element in the collection and
     * returns this element.
     *
     * @return mixed
     */
    public function last()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return end($this->fields);
    }

    /**
     * Gets the current key/index at the current internal iterator position.
     *
     * @return mixed
     */
    public function key()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return key($this->fields);
    }

    /**
     * Moves the internal iterator position to the next element.
     *
     * @return mixed
     */
    public function next()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return next($this->fields);
    }

    /**
     * Gets the element of the collection at the current internal iterator position.
     *
     * @return mixed
     */
    public function current()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return current($this->fields);
    }

    /**
     * Removes an element with a specific key/index from the collection.
     *
     * @param mixed $key
     * @return mixed The removed element or NULL, if no element exists for the given key.
     */
    public function remove($key)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        if (isset($this->fields[$key])) {
            $removed = $this->fields[$key];
            unset($this->fields[$key]);

            return $removed;
        }

        return null;
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeElement($element)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        $key = array_search($element, $this->fields, true);

        if ($key !== false) {
            unset($this->fields[$key]);

            return true;
        }

        return false;
    }

    /**
     * ArrayAccess implementation of offsetExists()
     *
     * @see containsKey()
     */
    public function offsetExists($offset)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return $this->containsKey($offset);
    }

    /**
     * ArrayAccess implementation of offsetGet()
     *
     * @see get()
     */
    public function offsetGet($offset)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return $this->get($offset);
    }

    /**
     * ArrayAccess implementation of offsetGet()
     *
     * @see add()
     * @see set()
     */
    public function offsetSet($offset, $value)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        if ( ! isset($offset)) {
            return $this->add($value);
        }
        
        return $this->set($offset, $value);
    }

    /**
     * ArrayAccess implementation of offsetUnset()
     *
     * @see remove()
     */
    public function offsetUnset($offset)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return $this->remove($offset);
    }

    /**
     * Checks whether the collection contains a specific key/index.
     *
     * @param mixed $key The key to check for.
     * @return boolean TRUE if the given key/index exists, FALSE otherwise.
     */
    public function containsKey($key)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return isset($this->fields[$key]);
    }

    /**
     * Checks whether the given element is contained in the collection.
     * Only element values are compared, not keys. The comparison of two fields
     * is strict, that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param mixed $element
     * @return boolean TRUE if the given element is contained in the collection,
     *          FALSE otherwise.
     */
    public function contains($element)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        foreach ($this->fields as $collectionElement) {
            if ($element === $collectionElement) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tests for the existance of an element that satisfies the given predicate.
     *
     * @param Closure $p The predicate.
     * @return boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    public function exists(Closure $p)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        foreach ($this->fields as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Searches for a given element and, if found, returns the corresponding key/index
     * of that element. The comparison of two fields is strict, that means not
     * only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param mixed $element The element to search for.
     * @return mixed The key/index of the element or FALSE if the element was not found.
     */
    public function indexOf($element)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return array_search($element, $this->fields, true);
    }

    /**
     * Gets the element with the given key/index.
     *
     * @param mixed $key The key.
     * @return mixed The element or NULL, if no element exists for the given key.
     */
    public function get($key)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        if (isset($this->fields[$key])) {
            return $this->fields[$key];
        }
        return null;
    }

    /**
     * Gets all keys/indexes of the collection fields.
     *
     * @return array
     */
    public function getKeys()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return array_keys($this->fields);
    }

    /**
     * Gets all fields.
     *
     * @return array
     */
    public function getValues()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return array_values($this->fields);
    }

    /**
     * Returns the number of fields in the collection.
     *
     * Implementation of the Countable interface.
     *
     * @return integer The number of fields in the collection.
     */
    public function count()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return count($this->fields);
    }

    /**
     * Adds/sets an element in the collection at the index / with the specified key.
     *
     * When the collection is a Map this is like put(key,value)/add(key,value).
     * When the collection is a List this is like add(position,value).
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        $this->fields[$key] = $value;
    }

    /**
     * Adds an element to the collection.
     *
     * @param mixed $value
     * @return boolean Always TRUE.
     */
    public function add($value)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        $this->fields[] = $value;
        return true;
    }

    /**
     * Checks whether the collection is empty.
     * 
     * Note: This is preferrable over count() == 0.
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return !$this->fields;
    }

    /**
     * Gets an iterator for iterating over the fields in the collection.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return new ArrayIterator($this->fields);
    }

    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the fields returned by the function.
     *
     * @param Closure $func
     * @return Collection
     */
    public function map(Closure $func)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return new static(array_map($func, $this->fields));
    }

    /**
     * Returns all the fields of this collection that satisfy the predicate p.
     * The order of the fields is preserved.
     *
     * @param Closure $p The predicate used for filtering.
     * @return Collection A collection with the results of the filter operation.
     */
    public function filter(Closure $p)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return new static(array_filter($this->fields, $p));
    }

    /**
     * Applies the given predicate p to all fields of this collection,
     * returning true, if the predicate yields true for all fields.
     *
     * @param Closure $p The predicate.
     * @return boolean TRUE, if the predicate yields TRUE for all fields, FALSE otherwise.
     */
    public function forAll(Closure $p)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        foreach ($this->fields as $key => $element) {
            if ( ! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * @param Closure $p The predicate on which to partition.
     * @return array An array with two fields. The first element contains the collection
     *               of fields where the predicate returned TRUE, the second element
     *               contains the collection of fields where the predicate returned FALSE.
     */
    public function partition(Closure $p)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        $coll1 = $coll2 = array();
        foreach ($this->fields as $key => $element) {
            if ($p($key, $element)) {
                $coll1[$key] = $element;
            } else {
                $coll2[$key] = $element;
            }
        }
        return array(new static($coll1), new static($coll2));
    }
    
    /**
     * Get a field
     *
     * @return Field
     */
    public function __get($key)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
       
        return $this->offsetGet($key);
    }
    
    /**
     * Set a field value
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        $this->offsetSet($key, $value);
    }
    
    /**
     * Check whether a field exists
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return $this->containsKey($key);
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * Clears the collection.
     */
    public function clear()
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        $this->fields = array();
    }

    /**
     * Extract a slice of $length fields starting at position $offset from the Collection.
     *
     * If $length is null it returns all fields from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the fields contained in the collection slice is called on.
     *
     * @param int $offset
     * @param int $length
     * @return array
     */
    public function slice($offset, $length = null)
    {   
        if (!$this->initialized) {
            $this->initialize();
        }
    
        return array_slice($this->fields, $offset, $length, true);
    }
}