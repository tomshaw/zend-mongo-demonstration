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
class App_Resource_Mongo extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @var unknown_type
     */
    protected $_db = null;

    /**
     * @see Zend_Application_Resource_Resource::init()
     */
    public function init ()
    {
        if (null !== ($options = $this->getOptions())) {
            if (sizeof($options)) {
                if ((null === $this->_db)) {
                    $this->_db = new App_Mongo_Db($options);
                }
            }
            return $this->_db;
        }
    }
}