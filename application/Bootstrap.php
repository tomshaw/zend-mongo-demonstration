<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initHasMongo()
    {
        if(!extension_loaded('Mongo')) {
            throw new Exception('The PHP Mongo driver is not loaded.');
        }
    }
	
    protected function _initAppAutoload ()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
        	'namespace' => '', 
        	'basePath' => APPLICATION_PATH
        ));
        return $loader;
    }
    
    protected function _initView()
    {
        $view = new Zend_View();
        
        $view->doctype('XHTML1_STRICT');
        
        $view->headTitle('Tom Shaw: Zend-MongoDB-Testing');
        
        $view->headLink()->appendStylesheet('/css/styles.css');
 
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        
        $viewRenderer->setView($view);
        
        return $view;
    }
}