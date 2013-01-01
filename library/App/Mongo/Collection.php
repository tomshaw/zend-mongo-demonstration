<?php

/**
 * Zend Framework and MongoDB Testing
 *
 * LICENSE: http://www.tomshaw.info/license
 *
 * @category   Tom Shaw
 * @package    Zend Framework and MongoDB Testing
 * @copyright  Copyright (c) 2011 Tom Shaw. (http://www.tomshaw.info)
 * @license    http://www.tomshaw.info/license   BSD License
 * @version    $Id:$
 * @since      File available since Release 1.0
 */
final class App_Mongo_Collection implements SeekableIterator, Countable, ArrayAccess
{

    /**
     * @var unknown_type
     */
    protected $_collection;

    /**
     * @var unknown_type
     */
    protected $_cursor;

    /**
     * @var unknown_type
     */
    protected $_data = array();

    /**
     * @var unknown_type
     */
    protected $_pointer = 0;

    /**
     * @var unknown_type
     */
    protected $_count;

    /**
     * @param unknown_type $config            
     */
    public function __construct (array $config = array())
    {
        if (isset($config['collection'])) {
            $this->_collection = $this->namespaceModel($config['collection']);
        }
        if (isset($config['cursor'])) {
            $this->setCursor($config['cursor']);
        }
        $this->count();
    }

    /**
     * Enter description here ...
     */
    public function getCursor ()
    {
        return $this->_cursor;
    }

    /** 
     * @param unknown_type $cursor            
     */
    public function setCursor ($cursor)
    {
        $this->_cursor = $cursor;
        return $this;
    }

    /**
     * Provides a fluent interface.
     * 
     * @param unknown_type $cursor            
     */
    public function setData ($cursor)
    {
        while ($cursor->hasNext()) {
            $this->_data[] = new $this->_collection($cursor->getNext());
        }
        return $this;
    }

    /**
     * @param string $key            
     */
    public function getData ($key = '')
    {
        if (empty($key)) {
            if (! sizeof($this->_data)) {
                $this->setData($this->_cursor);
            }
            return $this->_data;
        }
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    /**
     * @param unknown_type $className            
     * @param unknown_type $nameSpacePrefix            
     */
    protected function namespaceModel ($className, $nameSpacePrefix = 'Model_')
    {
        $modelName = $nameSpacePrefix . ucwords(strtolower($className));
        return $modelName;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SeekableIterator::seek()
     */
    public function seek ($position)
    {
        if (! $this->valid()) {
            throw new \OutOfBoundsException("Invalid position: ($position)");
        } else {
            $this->_pointer = $position;
        }
        return $this->_data[$this->_pointer];
    }

    /**
     * (non-PHPdoc)
     * 
     * @see Countable::count()
     */
    public function count ()
    {
        $this->_count = count($this->_data);
        return $this->_count;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SeekableIterator::rewind()
     */
    public function rewind ()
    {
        $this->_pointer = 0;
        return reset($this->_data);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SeekableIterator::key()
     */
    public function key ()
    {
        return key($this->_data);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SeekableIterator::current()
     */
    public function current ()
    {
        return $this->_data[$this->_pointer];
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SeekableIterator::valid()
     */
    public function valid ()
    {
        return isset($this->_data[$this->_pointer]);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see SeekableIterator::next()
     */
    public function next ()
    {
        return next($this->_data);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet ($key, $value)
    {
        if (! isset($key)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$key] = $value;
        }
        return true;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset ($key)
    {
        if (isset($this->_data[$key])) {
            unset($this->_data[$key]);
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet ($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists ($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * Enter description here ...
     */
    public function toArray ()
    {
        $this->setData($this->getCursor());
        return $this->_data;
    }
}