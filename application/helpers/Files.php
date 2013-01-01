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
class Zend_Controller_Action_Helper_Files extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $_form;
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $_adapter;
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $_uploadPath;
    /**
     * 
     * Enter description here ...
     */
    function __construct()
    {
        $this->_adapter    = new Zend_File_Transfer_Adapter_Http();
        $this->_uploadPath = ROOT_PATH . '/images/uploads';
    }
    /**
     * 
     * Enter description here ...
     */
    public function getUploadPath()
    {
        return $this->_uploadPath;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $path
     */
    public function setUploadPath($path)
    {
        $this->_uploadPath = $path;
    }
    /**
     * 
     * Enter description here ...
     * @param unknown_type $elementName
     * @param unknown_type $adapterOptions
     * @param unknown_type $renameOptions
     */
    public function direct($elementName, $adapterOptions = array(), $renameOptions = array())
    {
        $this->_adapter->setDestination($this->getUploadPath());
        
        try {
            if (!$this->_adapter->receive()) {
                return false;
            }
        }
        catch (Zend_File_Transfer_Exception $e) {
            return $e->getMessage();
        }
        
        $name = $this->_adapter->getFileName($elementName, false);
        
        $fullPathName = $this->_adapter->getFileName($elementName);
        
        if (sizeof($adapterOptions)) {
            $this->setOptions($adapterOptions);
            //$this->setOptions(array('useByteString' => false));
        }
        
        $size = $this->_adapter->getFileSize($elementName);
        
        $type = $this->_adapter->getMimeType($elementName);
        
        list($width, $height, $type, $attr) = getimagesize($fullPathName);
        
        if (sizeof($renameOptions)) {
            $newFileName       = (isset($renameOptions['name'])) ? (string) $renameOptions['name'] : 'pic.jpg';
            $overwriteExisting = (isset($renameOptions['overwrite'])) ? (bool) $renameOptions['overwrite'] : true;
            $this->renameFile($fullPathName, $newFileName, $renameOptions['overwrite']);
        }
        
        return array(
            'name' => $name,
            'path' => $fullPathName,
            'size' => $size,
            'type' => $type,
            'width' => $width,
            'height' => $height
        );
    }
    /**
     * 
     * Enter description here ...
     */
    public function setAdapterOptions()
    {
        $this->_adapter->setOptions(array(
            'useByteString' => false
        ));
    }
    /**
     * 
     * Enter description here ...
     * @param string $oldFileName
     * @param string $newFileName
     * @param bool $overwrite
     */
    public function renameFile($oldFileName, $newFileName = 'pic.jpg', $overwrite = true)
    {
        $fullPath     = $this->getUploadPath() . '/' . $newFileName;
        $filterRename = new Zend_Filter_File_Rename(array(
            'target' => $fullPath,
            'overwrite' => $overwrite
        ));
        $filterRename->filter($oldFileName);
        return $this;
    }
}