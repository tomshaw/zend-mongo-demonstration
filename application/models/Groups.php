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
class Model_Groups extends App_Mongo_Document
{
    /**
     * Collection name.
     * 
     * @var string
     */
    protected $_collection = 'groups';
    
    /**
     * 
     * Adds updated created at fields.
     * @var bool
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
            'created' => 1,
            'updated' => -1
        ));
    }
    
    /**
     * 
     * Enter description here ...
     * @param unknown_type $sort
     */
    public function fetchGroupsSelect($nodes = array(), $sort = array())
    {
        $iterator = $this->find(array(), $nodes);
        $cursor   = $iterator->getCursor();
        if (sizeof($sort)) {
            $cursor->sort($sort);
        }
        $documents  = $iterator->toArray();
        $returnData = array();
        foreach ($documents as $row) {
            $id              = (string) $row['_id'];
            $returnData[$id] = $row['name'];
        }
        return $returnData;
    }
}