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
abstract class App_Mongo_Document extends App_Mongo_Abstract
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
    protected $_connection;

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
     * @var unknown_type
     */
    protected $_externalIterator = 'App_Mongo_Collection';

    /**
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::connect()
     */
    public function connect ()
    {
        $this->_connection = $this->_db->selectCollection($this->_collection);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::close()
     */
    public function close ()
    {
        $this->_mongoDb->close();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::isConnected()
     */
    public function isConnected ()
    {
        return $this->_mongoDb->connected ? true : false;
    }

    /**
     * Enter description here ...
     */
    public function lastError ()
    {
        return $this->findOne(array('getlasterror'=>1));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::getApiVersion()
     */
    public function getApiVersion ()
    {
        return \Mongo::VERSION;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::getServerVersion()
     */
    public function getServerVersion ()
    {
        return $this->_db->command(array(
            '$cmd' => 'version()'
        ));
    }

    /**
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::command()
     * @todo test.
     */
    public function command ($data)
    {
        return $this->_db->selectCollection('$cmd')->findOne($data);
    }

    /**
     * Overridden by sub modules.
     * (non-PHPdoc)
     * 
     * @see App_Mongo_Abstract::setCollectionIndexes()
     */
    protected function setCollectionIndexes ()
    {}

    /**
     *
     *
     * Enter description here ...
     * 
     * @param mixed $id            
     */
    public function load ($id)
    {
        $data = $this->_connection->findOne(array(
            '_id' => $this->getMongoId($id)
        ));
        return $this->setData($data);
    }

    /**
     * Performs a find ensuring the cursor and collection are passed to the
     * iterator.
     *
     * @param array $filter            
     * @param array $nodes            
     */
    public function find ($filter = array(), $nodes = array())
    {
        $iterator = $this->_externalIterator;
        
        $this->_cursor = $this->_connection->find($filter, $nodes);
        
        $data = array(
            'collection' => $this->_collection,
            'cursor' => $this->_cursor
        );
        
        return new $iterator($data);
    }

    /**
     * @param unknown_type $params            
     */
    public function findOne ($params = array())
    {
        $data = $this->_connection->findOne($params);
        return $this->setData($data);
    }

    /**
     *
     *
     * Creates the document indexes that were defined in sub classes of
     * App_Mongo_Collection using the setCollectionIndexes() method.
     * @note The mongo ensure index method first checks if the index has been
     * created before creating the index. Indexes only need to be
     * created once per collection lifetime.
     *
     * @param bool $isProduction
     *            In production mode it is assumed the indexes have already been
     *            created and returns without running the ensure
     *            index method.
     * @param bool $dropIndexes
     *            Resets and drops all collection indexes.
     * @param mixed $dropIndex
     *            Drops specified index. db.collection.dropIndex({name: 1,
     *            created: -1})
     * @param array $indexOptions
     *            The specified index options ie 1 or -1.
     * @return App_Mongo_Document
     */
    protected function ensureIndex ($isProduction, $dropIndexes, $dropIndex, $indexOptions)
    {
        if ($isProduction) {
            return $this;
        }
        
        if ($dropIndexes) {
            $this->_connection->dropIndexs();
        }
        
        if ($dropIndex) {
            if (is_array($dropIndex)) {
                $this->_connection->dropIndex($dropIndex);
            }
        }
        
        if (sizeof($indexOptions)) {
            foreach ($indexOptions as $_index => $option) {
                $this->_connection->ensureIndex(array(
                        $_index => $option
                ));
            }
        }
        
        return $this;
    }

    /**
     * @param unknown_type $mixed            
     */
    public function save ()
    {
        $this->_beforeSave();
        if (! isset($this->_data['_id'])) {
            $this->_connection->insert($this->_data);
        } elseif (! is_object($this->_data['_id'])) {
            $this->_data['_id'] = $this->getMongoId($this->_data['_id']);
        }
        $this->_connection->save($this->_data);
        $this->_afterSave();
        return $this;
    }

    /**
     * Enter description here ...
     */
    private function _beforeSave ()
    {
        if ($this->_timeStampable === true) {
            if (! isset($this->_data['_id'])) {
                $this->_data['updated'] = $this->getMongoDate();
                $this->_data['created'] = $this->getMongoDate();
            }
            $this->_data['created'] = isset($this->_data['created']) ? $this->_data['created'] : $this->getMongoDate();
            $this->_data['updated'] = $this->getMongoDate();
        }
        // @todo streamline this seamlessly
        if (isset($this->_data['hash'])) {
            unset($this->_data['hash']);
        }
        if (isset($this->_data['submit'])) {
            unset($this->_data['submit']);
        }
        if (isset($this->_data['MAX_FILE_SIZE'])) {
            unset($this->_data['MAX_FILE_SIZE']);
        }
    }

    /**
     * Enter description here ...
     */
    private function _afterSave ()
    {}

    /**
     * @param unknown_type $obj            
     */
    public function insert ($obj)
    {
        $this->_connection->insert($obj);
    }

    /**
     * @param int $limit            
     */
    public function limit ($limit)
    {
        return $this->_cursor->limit($limit);
    }

    /**
     * @param array $sort            
     */
    public function sort ($sort = array())
    {
        return $this->_cursor->sort($sort);
    }

    /**
     * Enter description here ...
     */
    public function explain ()
    {
        return $this->_cursor->explain();
    }

    /**
     * @param unknown_type $criteria            
     * @param unknown_type $obj            
     */
    public function update ($criteria, $obj)
    {
        $this->_connection->update($criteria, $obj);
    }

    /**
     * @param unknown_type $criteria            
     * @param unknown_type $options            
     */
    public function delete ($criteria, $options = array())
    {
        return $this->_connection->remove($criteria, $options);
    }

    /**
     * @param unknown_type $code            
     * @param unknown_type $args            
     */
    public function execute ($code, $args)
    {
        return $this->command(array(
                '$eval' => $code,
                'args' => $args
        ));
    }
}