<?php

class ServerController extends Zend_Controller_Action
{
    public function init()
    {
    }
    
    public function indexAction()
    {
        $mongo = $this->getInvokeArg('bootstrap')->getResource('mongo');
        
        $db = $mongo->selectDatabase('admin')->selectCollection('$cmd');
        
        $this->view->status = $db->findOne(array(
            "serverStatus" => 1
        ));
        
        $this->view->buildInformation = $db->findOne(array(
            "buildinfo" => 1
        ));
        
        $this->view->directives = ini_get_all("mongo");
    }
}