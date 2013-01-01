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
class App_Plugin_Profile_Init extends Zend_Controller_Plugin_Abstract
{

    /**
     * @var unknown_type
     */
    private $hasRun = null;

    /**
     * @see Zend_Controller_Plugin_Abstract::postDispatch()
     */
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        // Make sure were not repetitively cycling thru this.
        if (true === $this->hasRun) {
            return false;
        } else {
            $this->hasRun = true;
        }
        
        $front = Zend_Controller_Front::getInstance();
        
        if ($front->getDispatcher()->isDispatchable($request)) {
            return;
        }
        
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        $user = new Model_User();
        
        if ($user->checkName($controller)) {
            $request->setModuleName('default')
                ->setControllerName('member')
                ->setActionName('view')
                ->setDispatched(true);
        }
        
        return;
    }
}