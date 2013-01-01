<?php

class App_Form_Validate_UniqueEmail extends Zend_Validate_Abstract
{
    const EMAIL_EXISTS = 'FOOBAR';

    protected $_messageTemplates = array(
        self::EMAIL_EXISTS => 'The email "%value%" already exists.',
    );

    protected $_model;

    public function __construct(Model_User $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        
        $user = $this->_model->checkEmail($value);
        
        if($user) {
        	$this->_error(self::EMAIL_EXISTS);
        	return false;	
        }
        
		return true;
    }
}
