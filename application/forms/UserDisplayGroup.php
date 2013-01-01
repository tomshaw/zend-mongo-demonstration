<?php

class Form_UserDisplayGroup extends Zend_Form
{
    protected $_elementDecorators = array('ViewHelper', 'Errors', 'Label', array(array('row' => 'HtmlTag'), array('tag' => 'li')));
    
    protected $_buttonDecorators = array('ViewHelper', array(array('data' => 'HtmlTag'), array('tag' => 'div')));
    
    protected $_captchaDecorators = array('Label', array(array('row' => 'HtmlTag'), array('tag' => 'li')));
    
    protected $_hiddenDecorators = array('ViewHelper', array(array('data' => 'HtmlTag'), array('tag' => 'span')));
    
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setAttrib('id', 'zendform');
    }
    
    public function init()
    {
        $email = $this->createElement('text', 'email');
        $email->setLabel('Email:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            5,
            50
        ))->addValidator('EmailAddress')->setValue('')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $password = $this->createElement('password', 'password');
        $password->setLabel('Password:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->setRequired(true)->setValue('')->setIgnore(false)->setDecorators($this->_elementDecorators);
        
        $confirmPassword = $this->createElement('password', 'confirmPassword');
        $confirmPassword->setLabel('Confirm Password:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->setRequired(true)->setValue('')->setIgnore(false)->setDecorators($this->_elementDecorators);
        
        /**
         * Billing information.
         */
        
        $billingFirstName = $this->createElement('text', 'first_name');
        $billingFirstName->setLabel('First Name')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->addErrorMessage('Your first name is required!')->setBelongsTo('billing')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $billingLastName = $this->createElement('text', 'last_name');
        $billingLastName->setLabel('Last Name:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->addErrorMessage('Your first name is required!')->setBelongsTo('billing')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $billingAddress1 = $this->createElement('text', 'address1');
        $billingAddress1->setLabel('Address1:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->setBelongsTo('billing')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $billingAddress2 = $this->createElement('text', 'address2');
        $billingAddress2->setLabel('Address2:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->setBelongsTo('billing')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $billingZip = $this->createElement('text', 'zip');
        $billingZip->setLabel('Zip code:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            15
        ))->setBelongsTo('billing')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $billingCity = $this->createElement('text', 'city');
        $billingCity->setLabel('City:')->setAttrib('size', 25)->setBelongsTo('billing')->setRequired(true)->setAttrib('tabindex', '6')->setDecorators($this->_elementDecorators);
        
        $billingState = $this->createElement('text', 'state');
        $billingState->setLabel('State:')->setAttrib('size', 6)->setAttrib('maxlength', 2)->setBelongsTo('billing')->setRequired(true)->setAttrib('tabindex', '7')->setDecorators($this->_elementDecorators);
        
        $billingCountry = $this->createElement('select', 'country');
        $billingCountry->setLabel('Country:')->setAttrib('class', 'select')->addMultiOptions($this->getCountryList())->setBelongsTo('billing')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        /**
         * 
         * Shipping information.
         */
        
        $shippingFirstName = $this->createElement('text', 'first_name');
        $shippingFirstName->setLabel('First Name')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->addErrorMessage('Your first name is required!')->setBelongsTo('shipping')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $shippingLastName = $this->createElement('text', 'last_name');
        $shippingLastName->setLabel('Last Name:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->addErrorMessage('Your first name is required!')->setBelongsTo('shipping')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $shippingAddress1 = $this->createElement('text', 'address1');
        $shippingAddress1->setLabel('Address1:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->setBelongsTo('shipping')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $shippingAddress2 = $this->createElement('text', 'address2');
        $shippingAddress2->setLabel('Address2:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            50
        ))->setBelongsTo('shipping')->setRequired(false)->setDecorators($this->_elementDecorators);
        
        $shippingZip = $this->createElement('text', 'zip');
        $shippingZip->setLabel('Zip code:')->setAttrib('size', 25)->addValidator('StringLength', false, array(
            3,
            15
        ))->setBelongsTo('shipping')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $shippingCity = $this->createElement('text', 'city');
        $shippingCity->setLabel('City:')->setAttrib('size', 25)->setBelongsTo('shipping')->setRequired(true)->setAttrib('tabindex', '6')->setDecorators($this->_elementDecorators);
        
        $shippingState = $this->createElement('text', 'state');
        $shippingState->setLabel('State:')->setAttrib('size', 6)->setAttrib('maxlength', 2)->setBelongsTo('shipping')->setRequired(true)->setAttrib('tabindex', '7')->setDecorators($this->_elementDecorators);
        
        $shippingCountry = $this->createElement('select', 'country');
        $shippingCountry->setLabel('Country:')->setAttrib('class', 'select')->addMultiOptions($this->getCountryList())->setBelongsTo('shipping')->setRequired(true)->setDecorators($this->_elementDecorators);
        
        $id = $this->createElement('hidden', '_id');
        $id->setDecorators($this->_hiddenDecorators);
        
        $hash = $this->createElement('hash', 'csrf', array(
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
        
        $submit = $this->createElement('submit', 'submit');
        $submit->setAttrib('id', 'submitbutton')->setDecorators($this->_buttonDecorators);
        
        $this->addElements(array(
            $billingFirstName,
            $billingLastName,
            $billingAddress1,
            $billingAddress2,
            $billingCountry,
            $billingCity,
            $billingState,
            $billingZip,
            $shippingFirstName,
            $shippingLastName,
            $shippingAddress1,
            $shippingAddress2,
            $shippingCountry,
            $shippingCity,
            $shippingState,
            $shippingZip,
            $email,
            $password,
            $confirmPassword,
            $id,
            $hash,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'email',
            'password',
            'confirmPassword'
        ), 'account', array(
            'legend' => 'Account Information'
        ));
        
        $account = $this->getDisplayGroup('account');
        $account->setDecorators(array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'ul'
                )
            ),
            'Fieldset'
        ));
        
        $this->addDisplayGroup(array(
            $billingFirstName,
            $billingLastName,
            $billingAddress1,
            $billingAddress2,
            $billingCountry,
            $billingCity,
            $billingState,
            $billingZip
        ), 'billing', array(
            'legend' => 'Billing Information'
        ));
        
        $billing = $this->getDisplayGroup('billing');
        $billing->setDecorators(array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'ul'
                )
            ),
            'Fieldset'
        ));
        
        $this->addDisplayGroup(array(
            $shippingFirstName,
            $shippingLastName,
            $shippingAddress1,
            $shippingAddress2,
            $shippingCountry,
            $shippingCity,
            $shippingState,
            $shippingZip
        ), 'shipping', array(
            'legend' => 'Shipping Information'
        ));
        
        $shipping = $this->getDisplayGroup('shipping');
        $shipping->setDecorators(array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'ul'
                )
            ),
            'Fieldset'
        ));
        
        $this->addDisplayGroup(array(
            'submit'
        ), 'submit');
        
    }
    
    public function getCountryList()
    {
        return array(
            'USA',
            'UK'
        );
    }
    
}