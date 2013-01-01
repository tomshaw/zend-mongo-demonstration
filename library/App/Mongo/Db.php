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
class App_Mongo_Db
{

    /**
     * @var unknown_type
     */
    protected static $_db;

    /**
     * @var unknown_type
     */
    protected static $_mongo;

    /**
     * @param unknown_type $options            
     * @throws MongoConnectionException
     */
    public function __construct ($options)
    {
        $connectionString = $this->getConnectionString($options);
        
        try {
            self::$_mongo = new \Mongo($connectionString);
            self::$_db = self::$_mongo->selectDB($options['dbname']);
        } catch (\MongoConnectionException $e) {
            throw new App_Mongo_Exception($e);
        }
    }

    /**
     * @param unknown_type $dbname            
     * @throws MongoConnectionException
     */
    public function selectDatabase ($dbname)
    {
        try {
            self::$_db = self::$_mongo->selectDB($dbname);
        } catch (\MongoConnectionException $e) {
            throw $e;
        }
        return $this;
    }

    /**
     * Enter description here ...
     */
    public static function getConnection ()
    {
        return self::$_mongo;
    }

    /**
     * Enter description here ...
     */
    public static function getDatabase ()
    {
        return self::$_db;
    }

    /**
     * @param unknown_type $collection            
     */
    public static function selectCollection ($collection)
    {
        return self::$_db->selectCollection($collection);
    }

    /**
     * @param unknown_type $options            
     * @throws \MongoConnectionException
     * @todo Add persistence configuration functionality.
     */
    private function getConnectionString ($options)
    {
        if (! is_array($options)) {
            throw new \MongoConnectionException("Connection options are not set.");
        }
        
        $connectionString = "mongodb://";
        if (isset($options['username']) && isset($options['password'])) {
            if (! empty($options['username'])) {
                $connectionString .= $options['username'] . ':';
            }
            if (! empty($options['password'])) {
                $connectionString .= $options['password'] . '@';
            }
        }
        $connectionString .= $options['host'] . ':';
        $connectionString .= $options['port'];
        
        return $connectionString;
    }
}