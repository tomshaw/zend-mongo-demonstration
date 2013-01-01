<?php

class App_Form_Form extends Zend_Form
{
	
	protected $_elementDecorators = array(
        'ViewHelper',
		'Errors',
		array(array('data'=>'HtmlTag'), array('tag' => 'li')),
		'Label',
        array('HtmlTag', array('tag'=>'li'))
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
        $this->setAttrib('id', 'user_edit'); 
    }
    
	public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
        	'fieldset',
            array('HtmlTag', array('tag' => 'ul')),
            'Form'
        ));

        $this->addDecorator('fieldset', array('id' => false));  
    }
}
