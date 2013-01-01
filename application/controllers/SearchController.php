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
class SearchController extends Zend_Controller_Action
{
    /**
     * 
     * Enter description here ...
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        
        $form = new Form_Search();
        
        $form->submit->setLabel('Submit');
        
        $form->getDecorator('fieldset')->setOption('legend', 'In Development');
        
        $form->setAction('/search');
        
        $this->view->form = $form;
        
        $store = new Zend_Session_Namespace('store');
        
        // If were not paginating or sorting results and there is no posted data, unset the store variable. 
        if (((null === $request->getParam('page')) && (null === ($request->getParam('sort')))) && (false === $request->isPost())) {
            unset($store->criteria);
        }
        
        if ($request->isPost()) {
            $formData = $request->getPost();
            
            if ($form->isValid($formData)) {
                $store = new Zend_Session_Namespace('store');
                
                $store->criteria = $this->_helper->MongoRegularExpression($formData);
                
                return $this->_forward('results');
                
            } else {
                $form->populate($formData);
            }
            
        } elseif (isset($store->criteria)) {
            // Search session stored.
            return $this->_forward('results');
        }
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function resultsAction()
    {
        $request = $this->getRequest();
        
        $page = (int) $request->getParam('page');
        
        $store = new Zend_Session_Namespace('store');
        
        $model = new Model_User();
        
        // Nodes we want returned by the query. Parameter can be excluded to return all results.
        $nodes = array(
            'first_name',
            'last_name',
            'email',
            'address',
            'phone',
            'created',
            'updated'
        );
        
        $documents = $model->find($store->criteria, $nodes);
        
        $paginator = new Zend_Paginator(new App_Mongo_Adapter_Paginator($documents->getCursor()));
        
        $paginator->setCurrentPageNumber($page);
        
        $paginator->setItemCountPerPage(5);
        
        $this->view->total = count($documents);
        
        $this->view->rows = $paginator;
    }
    
}