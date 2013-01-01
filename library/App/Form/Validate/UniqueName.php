<?php

class App_Form_Validate_UniqueName extends Zend_Validate_Abstract
{
    const NAME_TAKEN = 'FOOBAR';

    protected $_messageTemplates = array(
        self::NAME_TAKEN => 'The username "%value%" is not available.',
    );

    protected $_model;

    public function __construct(Model_User $model)
    {
        $this->_model = $model;
    }

    public function isValid($value, $context = null)
    {
        $this->_setValue($value);
        
        $user = $this->_model->checkName($value);
        
        if($user) {
        	$this->_error(self::NAME_TAKEN);
        	return false;	
        }
        
		return true;
    }
}
