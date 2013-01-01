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
abstract class App_Mongo_Abstract implements ArrayAccess, IteratorAggregate
{

    /**
     * @var unknown_type
     */
    protected $_data = array();

    /**
     * @var unknown_type
     */
    protected $_db = null;

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
    protected $_mongoDb;

    /**
     * @var unknown_type
     */
    protected $_timeStampable = false;

    /**
     * @param array $data            
     */
    public function __construct (array $data = array())
    {
        if (! sizeof($this->_data)) {
            foreach ($data as $i => $value) {
                $this->_data[$i] = $value;
            }
        }
        
        $this->_mongoDb = App_Mongo_Db::getConnection();
        
        $this->_db = App_Mongo_Db::getDatabase();
        
        if (sizeof($this->_data)) {
            return;
        }
        
        $this->connect();
        
        $this->setCollectionIndexes();
    }

    /**
     * @param array $data            
     */
    public function setData ($data = array())
    {
        if (sizeof($data)) {
            foreach ($data as $i => $value) {
                $this->_data[$i] = $value;
            }
        }
        return $this->_data;
    }

    /**
     * Enter description here ...
     */
    public function __destruct ()
    {
        $this->close();
    }

    /**
     * @param unknown_type $name            
     * @param unknown_type $value            
     */
    public function __set ($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * @param unknown_type $name            
     */
    public function __get ($name)
    {
        return $this->_data[$name];
    }

    /**
     * @param unknown_type $method            
     * @param unknown_type $params            
     */
    function __call ($method, $params)
    {
        if (substr($method, 0, 6) == 'findBy') {
            $document = $this->normalizeProperty(substr($method, 6));
            return $this->find(array(
                    $document => $params[0]
            ));
        } elseif (substr($method, 0, 9) == 'findOneBy') {
            $document = $this->normalizeProperty(substr($method, 9));
            return $this->findOne(array(
                    $document => $params[0]
            ));
        } else {
            if (substr($method, 0, 3) == 'get') {
                $property = $this->normalizeProperty(substr($method, 3));
                return $this->_data[$property];
            } elseif (substr($method, 0, 3) == 'set') {
                $property = $this->normalizeProperty(substr($method, 3));
                return $this->_data[$property] = array_shift($params);
            }
        }
        throw new \BadMethodCallException(
                sprintf("Trapped method %s::%s(%s)", get_class($this), $method, 
                        print_r($params, 1)));
    }

    /**
     * @param unknown_type $id            
     */
    public function getMongoId ($id)
    {
        return new \MongoId($id);
    }

    /**
     * @param unknown_type $id            
     */
    public function getMongoDate ()
    {
        return new \MongoDate();
    }

    /**
     * @param string $collectionName            
     * @param mixed $reference            
     */
    public function createDbRef ($collectionName, $reference)
    {
        return \MongoDBRef::create($collectionName, 
                $this->getMongoId($reference));
    }

    /**
     * Enter description here ...
     */
    public function getGridFS ()
    {
        return $this->_db->getGridFS();
    }

    /**
     * @param unknown_type $ref            
     */
    public function getDbRef ($ref)
    {
        return $this->_db->getDBRef($ref);
    }

    /**
     * @param string $string            
     */
    protected function normalizeProperty ($string)
    {
        $string = preg_replace('/(.)([A-Z])/', '$1_$2', $string);
        return strtolower($string);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet ($offset, $value)
    {
        if (is_null($offset)) {
            $this->_data[] = $value;
        } else {
            $this->_data[$offset] = $value;
        }
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists ($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset ($offset)
    {
        unset($this->_data[$offset]);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet ($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator ()
    {
        return new ArrayIterator((array) $this->_data);
    }

    /**
     * Enter description here ...
     */
    public function toArray ()
    {
        return (array) $this->_data;
    }

    /**
     * Enter description here ...
     */
    abstract public function connect ();

    /**
     * Enter description here ...
     */
    abstract public function close ();

    /**
     * Enter description here ...
     */
    abstract public function isConnected ();

    /**
     * Enter description here ...
     */
    abstract public function lastError ();

    /**
     * Enter description here ...
     */
    abstract public function getApiVersion ();

    /**
     * Enter description here ...
     */
    abstract public function getServerVersion ();

    /**
     * Enter description here ...          
     */
    abstract public function command ($command);

    /**
     * Enter description here ...
     */
    abstract protected function setCollectionIndexes ();
}