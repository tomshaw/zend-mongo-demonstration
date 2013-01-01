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
class Zend_Controller_Action_Helper_MongoRegularExpression extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * 
     * Enter description here ...
     * @param array $params
     * @return mixed
     */
    function direct($params = array())
    {
        $expunge = self::getExpungables();
    	
        $criteria = array();
        array_walk($params, function($value, $_index) use ($expunge, &$criteria) {
            if (!in_array($_index,$expunge) && !empty($value)) {
                $criteria[$_index] = new \MongoRegex("/^{$value}/i");
            }
        });
        return $criteria;
    }
    
    /**
     * 
     * Enter description here ...
     */
    private static function getExpungables()
    {
        return array('controller','action','module','hash','submit','id');
    }
    
}