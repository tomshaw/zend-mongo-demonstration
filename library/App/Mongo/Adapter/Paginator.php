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
/**
 * Implements Zend_Paginator_Adapter_Interface
 *
 * Allows pagination of MongoDB collections
 */
class App_Mongo_Adapter_Paginator implements Zend_Paginator_Adapter_Interface
{
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $_cursor;
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $cursor
     */
    public function __construct(MongoCursor $cursor)
    {
        $this->_cursor = $cursor;
    }
    
    /**
     * (non-PHPdoc)
     * @see Zend_Paginator_Adapter_Interface::getItems()
     */
    public function getItems($offset, $limit)
    {
        if ($offset) {
            $this->_cursor->skip($offset);
        }
        
        if ($limit) {
            $this->_cursor->limit($limit);
        }
        
        $data = array();
        while ($this->_cursor->hasNext()) {
            $data[] = $this->_cursor->getNext();
        }
        return $data;
    }
    
    /**
     * (non-PHPdoc)
     * @see Countable::count()
     */
    public function count()
    {
        return $this->_cursor->count();
    }
}