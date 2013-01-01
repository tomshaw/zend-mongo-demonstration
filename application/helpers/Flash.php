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
/**
 * A convenience wrapper used to access the FlashMessenger helper. Below are some example methods of using
 * this utility in your controller.
 * 
 *    $this->_helper->flash->addMessage('Message 1');
 *    $this->_helper->flash->addMessage('Message 2');
 *    $this->_helper->flash->addUrl('/admin/resources');
 *    $this->_helper->flash->addMessage('Message 3');
 *    $this->_helper->flash->addStatus('success');
 *  $this->_helper->flash->addReturn('You successfully completed the exam. Click here to return back to the main menu.');
 *    $this->_helper->flash();
 * 
 *  The direct method requires at the very least a single message to be sent to the view helper.
 * 
 *    $this->_helper->flash(array('message' => 'Resource was created successfully.', 'status' => 'success', 'url' => '/admin/resources'));
 */
class Zend_Controller_Action_Helper_Flash extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    protected $redirector;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    protected $messenger;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    protected $controller;
    
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    protected $module;
    
    /**
     * Enter description here...
     *
     */
    function __construct()
    {
        if (null === $this->redirector) {
            $this->redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        }
        if (null === $this->messenger) {
            $this->messenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        }
        if (null === $this->controller) {
            $this->controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        }
        if (null === $this->module) {
            $this->module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        }
    }
    
    /**
     * The direct method.
     *
     * 
     * @param  array|null $data 
     * @return void
     */
    public function direct($data = null)
    {
        if (null === $data) {
            $this->redirector->gotoUrl('/flash');
        }
        
        $this->buildArray($data);
        
        if ('default' === $this->module) {
            $this->redirector->gotoUrl('/flash');
        } else {
            $this->redirector->gotoUrl('/' . $this->module . '/flash');
        }
    }
    
    /**
     * Adds a message key|value to the flashMessenger.
     * 
     * @return void
     */
    public function addMessage($message)
    {
        return $this->messenger->addMessage(array(
            'message' => $message
        ));
    }
    
    /**
     * Adds a url key|value to the flashMessenger.
     * 
     * @return void
     */
    public function addUrl($url)
    {
        return $this->messenger->addMessage(array(
            'url' => $url
        ));
    }
    
    /**
     * Adds a status key|value to the flashMessenger.
     * 
     * @return void
     */
    public function addStatus($status)
    {
        return $this->messenger->addMessage(array(
            'status' => $status
        ));
    }
    
    /**
     * Adds a return key|value to the flashMessenger pre translated.
     * 
     * @return void
     */
    public function addReturn($str)
    {
        return $this->messenger->addMessage(array(
            'return' => $str
        ));
    }
    
    /**
     * Formats array into an easy to use structure.
     * 
     * @return void
     */
    private function buildArray($data = array())
    {
        if (isset($data['return'])) {
            $this->addReturn($data['return']);
        } else {
            $this->addReturn('Click here to return back to the previous menu...');
        }
        if (isset($data['url'])) {
            $this->addUrl($data['url']);
        } else {
            $this->addUrl('/' . $this->controller);
        }
        if (isset($data['message'])) {
            $this->addMessage($data['message']);
        } else {
            $this->addMessage('No messages were specified');
        }
        if (isset($data['status'])) {
            $this->addStatus($data['status']);
        } else {
            $this->addStatus('success');
        }
    }
    
}