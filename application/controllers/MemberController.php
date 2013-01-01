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
class MemberController extends Zend_Controller_Action
{
    /**
     * 
     * Enter description here ...
     */
    public function indexAction()
    {
        $mongo = new Model_User();
        
        $documents = $mongo->find();
        
        $cursor = $documents->getCursor();
        
        // Needed to access database reference calls.
        $this->view->mongo = $mongo;
        
        $request = $this->getRequest();
        
        $page = (int) $this->_getParam('page');
        
        //$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($documents->toArray()));
        $paginator = new Zend_Paginator(new App_Mongo_Adapter_Paginator($cursor));
        
        $paginator->setCurrentPageNumber($page);
        
        $paginator->setItemCountPerPage(5);
        
        $this->view->total = $documents->count();
        
        $this->view->rows = $paginator;
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function addAction()
    {
        $form = new Form_User();
        
        // Sets the submit button label.
        $form->submit->setLabel('Create User');
        
        // Removes the id element since we are creating a new row.
        $form->removeElement('_id');
        
        // Removes the assigned group, defaults to member automatically.
        $form->user->removeElement('group');
        
        // In add mode a user password is required.
        $form->user->password->setRequired(true);
        
        // Assigns the post action to controller hasone action add.
        $form->setAction('/member/add');
        
        // Assigns the form to the view layer.
        $this->view->form = $form;
        
        $errors = array();
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if ($form->isValid($formData)) {
                $user = new Model_User();
                
                $email = (string) $formData['email'];
                
                $password = (string) $formData['password'];
                
                if (!Zend_Validate::is(trim($password), 'NotEmpty')) {
                    $errors[] = 'You must choose a password.';
                }
                
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    $errors[] = 'The email address entered could not be validated.';
                }
                
                if (!Zend_Validate::is($password, 'StringLength', array(
                    'min' => 6
                ))) {
                    $errors[] = 'Passwords must be atleast 6 characters.';
                }
                
                $formData['password'] = md5($password);
                
                $groups = new Model_Groups();
                
                $groupData = $groups->findOneByName('Member');
                
                $formData['group'] = $user->createDbRef('groups', (string) $groupData['_id']);
                
                if (sizeof($errors)) {
                    return $form->populate($formData);
                }
                
                $user->setData($formData);
                
                $user->save();
                
                $this->_helper->redirector('index');
                
            } else {
                return $form->populate($formData);
            }
        }
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function editAction()
    {
        $form = new Form_User();
        
        // Sets the submit button label.
        $form->submit->setLabel('Update User');
        
        // Removes username since we only wnat to set it once.
        $form->user->removeElement('username');
        
        // removes email. @todo use sessions to check current email.
        $form->user->removeElement('email');
        
        // Defines the post action.
        $form->setAction('/member/edit');
        
        $this->view->form = $form;
        
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            
            if ($form->isValid($formData)) {
                $mongo = new Model_User();
                
                $data = $mongo->load($formData['_id']);
                
                $formData['group'] = $mongo->createDbRef('groups', $formData['group']);
                
                $formData['password'] = $formData['password'] ? md5($formData['password']) : $data['password'];
                
                $adapter = $this->_helper->Files($element = 'avatar');
                
                if (is_array($adapter)) {
                    $gridFs = $mongo->getGridFs();
                    
                    $formData['avatar'] = $gridFs->storeFile($adapter['path'], array(
                        'metadata' => array(
                            'name' => $adapter['name'],
                            'size' => $adapter['size'],
                            'type' => $adapter['type'],
                            'width' => $adapter['width'],
                            'height' => $adapter['height'],
                            'date' => $mongo->getMongoDate()
                        )
                    ));
                }
                
                $mongo->setData($formData);
                
                $mongo->save();
                
                $this->_helper->redirector('index');
                
            } else {
                return $form->populate($formData);
            }
            
        } else {
            if (null !== ($id = $this->getRequest()->getParam('id'))) {
                $user = new Model_User();
                
                $row = $user->load($id);
                
                // Cast the mongoid object id to string.
                $row['_id'] = (string) $row['_id'];
                
                $form->setDefaults($row);
            }
        }
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function viewAction()
    {
        $request = $this->getRequest();
        
        $name = (string) $request->getParam('controller');
        
        $user = new Model_User();
        
        $row = $user->findOneByUsername($name);
        
        $row = $user->findOneByUsername($name);
        
        $grid = $user->getGridFS();
        
        $file = $grid->findOne(array(
            '_id' => $user->getMongoId((string) $row['avatar'])
        ));
        
        $this->view->image = $file->file;
        
        $this->view->row = $row;
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function renderAction()
    {
        $request = $this->getRequest();
        
        $name = (string) $request->getParam('name');
        
        $this->_helper->viewRenderer->setNoRender();
        
        $this->_helper->getHelper('layout')->disableLayout();
        
        $user = new Model_User();
        
        $row = $user->findOneByUsername($name);
        
        $grid = $user->getGridFS();
        
        $file = $grid->findOne(array(
            '_id' => $user->getMongoId((string) $row['avatar'])
        ));
        
        header('Content-Type: image/jpeg');
        
        echo $file->getBytes();
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function deleteAction()
    {
        $id = (string) $this->getRequest()->getParam('id');
        if ($id) {
            $mongo = new Model_User();
            $mongo->delete(array(
                '_id' => $mongo->getMongoId($id)
            ));
        }
        $this->_helper->redirector('index');
    }
}