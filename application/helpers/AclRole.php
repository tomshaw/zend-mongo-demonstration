<?php
/**
 * There's two methods to access the method in Zend Controller Action Helpers.
 * 1. Using the direct() method by create a method named direct() and accessing it via your controller
 * $response = $this->_helper->Helper();
 *
 * 2. By initialising the helper object by name and then accessing the method like any other object.
 * $obj = $this->_helper->getHelper('Obj');
 * $obj->foo();
 */
class Zend_Controller_Action_Helper_AclRole extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    private $_roles = array('anonymous', 'member', 'author', 'moderator', 'administrator');
    /**
     * Enter description here...
     *
     */
    function role($roleId)
    {
        if (isset($this->_roles[$roleId])) {
            return $this->_roles[$roleId];
        }
        return 'Unknown';
    }
    
    function getRoles()
    {
        return $this->_roles;
    }
    
}