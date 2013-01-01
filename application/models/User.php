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
class Model_User extends App_Mongo_Document
{
    /**
     * Collection name.
     * 
     * @var string
     */
    protected $_collection = 'user';
    
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $_timeStampable = true;
    
    /**
     * Creates, drops indexes on the collection.
     * @see App_Mongo_Document ensureIndex()
     */
    protected function setCollectionIndexes()
    {
        $this->ensureIndex($isProduction = false, $dropIndexes = false, $dropIndex = null, $indexes = array(
            'name' => 1,
            'email' => 1,
            'created' => 1,
            'updated' => -1
        ));
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $name
     */
    public function checkName($name)
    {
        $row = $this->findOneByUsername($name);
        if (sizeof($row)) {
            return true;
        }
        return false;
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $email
     */
    public function checkEmail($email)
    {
        $row = $this->findOneByEmail($email);
        if (sizeof($row)) {
            return true;
        }
        return false;
    }
}