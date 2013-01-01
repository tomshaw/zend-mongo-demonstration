<?php

class Zend_View_Helper_ServerSettingsValue
{
    public $view;
    
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function ServerSettingsValue($value)
    {
        if (empty($value)) {
            return 'false';
        } elseif (is_array($value)) {
            return Zend_Json_Encoder::encode($value);
        }
        return $value;
    }
    
}