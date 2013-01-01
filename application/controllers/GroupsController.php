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
class GroupsController extends Zend_Controller_Action
{
    /**
     * 
     * Enter description here ...
     * @throws MongoException
     */
    public function indexAction()
    {
        $groups = new Model_Groups();
        
        $iterator = $groups->find();
        
        $cursor = $iterator->getCursor();
        
        $cursor->sort(array(
            'sort' => 1
        ));
        
        $request = $this->getRequest();
        
        $page = (int) $this->_getParam('page');
        
        $paginator = new Zend_Paginator(new App_Mongo_Adapter_Paginator($cursor));
        
        $paginator->setCurrentPageNumber($page);
        
        $paginator->setItemCountPerPage(5);
        
        $this->view->total = $iterator->count();
        
        $this->view->rows = $paginator;
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function addAction()
    {
        $form = new Form_Group();
        
        $form->submit->setLabel('Save');
        
        $form->getDecorator('fieldset')->setOption('legend', 'Create New Group');
        
        $form->removeElement('_id');
        
        $form->setAction('/groups/add');
        
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if ($form->isValid($formData)) {
                $group = new Model_Groups();
                
                $group->setData($formData);
                
                $group->save();
                
                $this->_helper->redirector('index');
                
            } else {
                $form->populate($formData);
            }
        }
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function editAction()
    {
        $form = new Form_Group();
        
        $form->submit->setLabel('Update');
        
        $form->getDecorator('fieldset')->setOption('legend', 'Edit Group');
        
        $form->setAction('/groups/edit');
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if ($form->isValid($formData)) {
                if (null !== ($id = $formData['_id'])) {
                    $mongo = new Model_Groups();
                    
                    $mongo->load($id);
                    
                    $mongo->setData($formData);
                    
                    $mongo->save();
                    
                    $this->_helper->redirector('index');
                    
                } else {
                    $form->populate($formData);
                }
            } else {
                $form->populate($formData);
            }
            
        } else {
            if (null !== ($id = $this->getRequest()->getParam('id'))) {
                $mongo = new Model_Groups();
                
                $data = $mongo->load((string) $id);
                
                $form->populate($data);
                
            }
        }
        
        $this->view->form = $form;
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function deleteAction()
    {
        $id = (string) $this->getRequest()->getParam('id');
        if ($id) {
            $mongo = new Model_Groups();
            $mongo->delete(array(
                '_id' => $mongo->getMongoId($id)
            ));
        }
        $this->_helper->redirector('index');
    }
}