<?php
/**
 * Enter description here...
 *
 */
class Zend_Controller_Action_Helper_Settings extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Enter description here...
     *
     * @return unknown
     */
    function direct($file)
    {
    	$includeFile = false;
        if(file_exists(APPLICATION_PATH . '/configs/application.ini')) {
    		$includeFile = APPLICATION_PATH . '/configs/application.ini';
    	} 
    	$config = new Zend_Config_Ini($includeFile);
    	return $config->toArray();
    }
    
}
