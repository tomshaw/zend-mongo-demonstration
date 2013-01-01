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
class Form_Group extends App_Form_Form
{
    /**
     * (non-PHPdoc)
     * @see Zend_Form::init()
     */
    public function init()
    {
        $this->setElementFilters(array(
            'StringTrim',
            'StripTags'
        ));
        
        $this->setName('search_form')->setMethod('post');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Group Name:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $sort = new Zend_Form_Element_Text('sort');
        $sort->setLabel('Sort order:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $id = new Zend_Form_Element_Hidden('_id');
        $id->setDecorators($this->_hiddenDecorators);
        
        $hash = new Zend_Form_Element_Hash('hash', 'foo_bar', array(
            'salt' => 'unique'
        ));
        
        $hash->setDecorators(array(
            array(
                'ViewHelper',
                array(
                    'helper' => 'formHidden'
                )
            )
        ));
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')->setDecorators($this->_buttonDecorators);
        
        $this->addElements(array($name,$sort,$id,$hash,$submit));
    }
    
}