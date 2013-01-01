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
class Form_Search extends App_Form_Form
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
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $firstName = new Zend_Form_Element_Text('first_name');
        $firstName->setLabel('First name:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $lastName = new Zend_Form_Element_Text('last_name');
        $lastName->setLabel('Last name:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Phone:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Home address:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $hash = new Zend_Form_Element_Hash('hash', 'foo_bar_baz', array(
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
        
        $this->addElements(array($email,$firstName,$lastName,$phone,$address,$hash,$submit));
    }
    
}