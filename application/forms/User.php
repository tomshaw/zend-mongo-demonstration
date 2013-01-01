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
/**
 * This is a shared form for both adding and editing users.
 * @author Tom Shaw
 */
class Form_User extends Zend_Form
{
    protected $_elementDecorators = array('ViewHelper', 'Errors', 'Label', array(array('row' => 'HtmlTag'), array('tag' => 'li')));
    
    protected $_checkboxDecorators = array('ViewHelper', 'Description', 'Errors', 'Label', array(array('row' => 'HtmlTag'), array('tag' => 'li')));
    
    // @see http://forums.zend.com/viewtopic.php?f=69&t=4023
    protected $_fileDecorators = array('File', 'Description', 'Errors', 'Label', array(array('row' => 'HtmlTag'), array('tag' => 'li')));
    
    protected $_buttonDecorators = array('ViewHelper', array(array('data' => 'HtmlTag'), array('tag' => 'div')));
    
    protected $_captchaDecorators = array('Label', array(array('row' => 'HtmlTag'), array('tag' => 'li')));
    
    protected $_hiddenDecorators = array('ViewHelper', array(array('data' => 'HtmlTag'), array('tag' => 'span')));
    
    /**
     * (non-PHPdoc)
     * @see Zend_Form::loadDefaultDecorators()
     */
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
        $this->setSubFormDecorators(array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'ul'
                )
            ),
            'Fieldset'
        ));
    }
    
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
        
        $this->setName('zendform');
        
        $this->setMethod('post');
        
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        
        $this->addElementPrefixPath('App_Form_Validate', 'App/Form/Validate/', 'validate');
        
        $userInfo = new Zend_Form_SubForm(array(
            'elements' => array(
                'username' => 'text',
                'first_name' => 'text',
                'last_name' => 'text',
                'email' => 'text',
                'password' => 'password',
                'avatar' => 'file',
                'group' => 'select',
                'bio' => 'textarea',
                'newsletter' => 'checkbox'
            ),
            'legend' => 'User Information',
            'elementsBelongTo' => ''
        ));
        
        $userInfo->username->setLabel('Username:')->setRequired(true)->setValidators(array(
            array(
                'UniqueName',
                true,
                array(
                    new Model_User()
                )
            ),
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 1,
                    'max' => 30
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $userInfo->first_name->setLabel('First name:')->setRequired(true)->setValidators(array(
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 1,
                    'max' => 30
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $userInfo->last_name->setLabel('Last name:')->setRequired(true)->setValidators(array(
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 1,
                    'max' => 30
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $userInfo->email->setLabel('Email address:')->setRequired(true)->setFilters(array(
            'StringToLower'
        ))->setValidators(array(
            array(
                'EmailAddress',
                true
            ),
            array(
                'UniqueEmail',
                true,
                array(
                    new Model_User()
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $config = $this->uploadSettings();
        
        $userInfo->avatar->setLabel('Your photograph:')->setRequired(false)->setFilters(array(
            'StringToLower'
        ))->setValidators(array(
            array(
                'Extension',
                true,
                $config->params->include->toArray()
            ),
            array(
                'ExcludeExtension',
                false,
                $config->params->exclude->toArray()
            ),
            array(
                'Count',
                true,
                array(
                    $config->params->count->min,
                    $config->params->count->max
                )
            ),
            array(
                'Size',
                true,
                array(
                    $config->params->size->min,
                    $config->params->size->max
                )
            ),
            array(
                'FilesSize',
                array(
                    $config->params->filessize->min,
                    $config->params->filessize->max
                ),
                array(
                    'ImageSize',
                    true,
                    array(
                        $config->params->imagesize->minwidth,
                        $config->params->imagesize->maxwidth,
                        $config->params->imagesize->minheight,
                        $config->params->imagesize->maxheight
                    )
                )
            )
        ))->setDecorators($this->_fileDecorators);
        
        $userInfo->group->setLabel('Assigned Group:')->setRequired(true)->setMultiOptions($this->groupOptions())->setDecorators($this->_elementDecorators);
        
        $userInfo->password->setLabel('Password:')->setRequired(false)->setValidators(array(
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 6,
                    'max' => 32,
                    'messages' => array(
                        'stringLengthTooShort' => 'Passwords must be at least 6 characters',
                        'stringLengthTooLong' => 'Passwords must be no more than 32 characters'
                    )
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $userInfo->bio->setLabel('Your biography:')->setRequired(true)->setAttrib('COLS', '100')->setAttrib('ROWS', '20')->setAttrib('style', 'width:70%;padding:5px;')->setDecorators($this->_elementDecorators);
        
        $userInfo->newsletter->setLabel('Newsletter:')
        // FIXME decorator hack.
            ->setAttrib('style', 'width:5%;margin-top:7px;margin-left:-15px;')->setDecorators($this->_checkboxDecorators);
        
        $billing = new Zend_Form_SubForm(array(
            'elements' => array(
                'name' => 'text',
                'address1' => 'text',
                'address2' => 'text',
                'country' => 'select',
                'city' => 'text',
                'state' => 'text',
                'zip' => 'text'
            ),
            'legend' => 'Billing Information',
            'elementsBelongTo' => 'address[billing]'
        ));
        
        $billing->name->setLabel('Name:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $billing->address1->setLabel('Street:')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $billing->address2->setLabel('Street:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $billing->country->setLabel('Country:')->setRequired(true)->setMultiOptions($this->countryList())->setDecorators($this->_elementDecorators);
        
        $billing->city->setLabel('City:')->setRequired(true)->setValidators(array(
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 2,
                    'max' => 20
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $billing->state->setLabel('State:')->setRequired(true)->setValidators(array(
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 5,
                    'max' => 20
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $billing->zip->setLabel('Zip code:')->setRequired(true)->setValidators(array(
            'alnum'
        ))->setFilters(array(
            'StringToLower'
        ))->setDecorators($this->_elementDecorators);
        
        $shipping = new Zend_Form_SubForm(array(
            'elements' => array(
                'name' => 'text',
                'address1' => 'text',
                'address2' => 'text',
                'country' => 'select',
                'city' => 'text',
                'state' => 'text',
                'zip' => 'text'
            ),
            'legend' => 'Shipping Information',
            'elementsBelongTo' => 'address[shipping]'
        ));
        
        $shipping->name->setLabel('Name:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $shipping->address1->setLabel('Street:')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $shipping->address2->setLabel('Street:')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $shipping->country->setLabel('Country:')->setRequired(true)->setMultiOptions($this->countryList())->setDecorators($this->_elementDecorators);
        
        $shipping->city->setLabel('City:')->setRequired(true)->setValidators(array(
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 2,
                    'max' => 20
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $shipping->state->setLabel('State:')->setRequired(true)->setValidators(array(
            'alnum',
            array(
                'validator' => 'StringLength',
                'options' => array(
                    'min' => 5,
                    'max' => 20
                )
            )
        ))->setDecorators($this->_elementDecorators);
        
        $shipping->zip->setLabel('Zip code:')->setRequired(true)->setValidators(array(
            'alnum'
        ))->setFilters(array(
            'StringToLower'
        ))->setDecorators($this->_elementDecorators);
        
        $this->addSubForm($userInfo, 'user')->addSubForm($billing, 'billing')->addSubForm($shipping, 'shipping');
        
        $hash = new Zend_Form_Element_Hash('hash', 'csrf', array(
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
        
        $id = new Zend_Form_Element_Hidden('_id');
        $id->setDecorators($this->_hiddenDecorators);
        
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submit')->setDecorators($this->_buttonDecorators);
        
        $this->addElements(array($id,$hash,$submit));
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function uploadSettings()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/uploads.ini', 'production');
        return $config->imageuploads;
    }
    
    /** 
     * Queries system groups for sub form group select.
     */
    public function groupOptions()
    {
        $groups     = new Model_Groups();
        $selectData = $groups->fetchGroupsSelect(array('_id','name'), array('sort' => 1));
        return $selectData;
    }
    
    /**
     * 
     * Enter description here ...
     */
    public function countryList()
    {
        return array(
            'US' => 'United States',
            'CA' => 'Canada',
            'UK' => 'United Kingdom'
        );
    }
    
}