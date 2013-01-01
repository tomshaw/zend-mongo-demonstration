<?php

class App_Form_Subform extends Zend_Form
{   
	protected $_elementDecorators = array(
        'ViewHelper',
		'Errors',
        'Label',
        array(array('row' => 'HtmlTag'), array('tag' => 'li'))
    );
	
    protected $_buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div'))
    );
 
    protected $_captchaDecorators = array(
        'Label',
        array(array('row' => 'HtmlTag'), array('tag' => 'li'))
    );
    
    protected $_hiddenDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'span')),
    );
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setAttrib('id', 'subform'); 
    }
    
	public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'ul')),
            'Form',
        ));
//		$this->setSubFormDecorators(array(
//		    'FormElements',
//		    'Fieldset'
//		));
        //$this->addDecorator('fieldset', array('id' => false));  
    }
}
