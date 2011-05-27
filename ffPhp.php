<?php

if(!defined('LF')) define('LF', "\n");

class ffException extends exception;

require_once "ffObject.php";

require_once "ffFieldset.php";
require_once "ffInput.php";
require_once "ffButton.php";

class ffPhp extends ffObject {
    protected $allowedProperties = array('uniqueId' => array('type' => 'u+int'),
                                         'id'       => array('type' => 'string', 'default' => ''),
                                         'action'   => array('type' => 'string', 'default' => $_SERVER['SCRIPT_NAME']),
                                         'method'   => array('type' => 'string', 'callback' => 'SetRequestArray'),
                                         'oddHighlight' => array('type' => 'bool', 'default' => true),
                                         'cssClass' => array('type' => 'array',  'default' => array()));
    public $req;
    
    private $multipart = false;
    private $openfieldset = false;
    
    private $controls = array();
    private $hiddenControls = array();
    private $buttons = array();
    
    public function __construct($method = 'post') {
        $this->method = $method;
        
        static $uniqueId = 1;
        $this->id = $uniqueId++; //Increment for next instance
    }
    
    public function Add(ffiControl $control) {
        if($control instanceof ffiButtonControl) {
            $this->buttons[] = $control;
        } else if($control instanceof ffiHiddenControl) {
            $this->hiddenControls[] = $control;
        } else {
            if(empty($this->controls) && !($control instanceof ffFieldset)) {
                $this->Add(new ffFieldset);
            }
            $this->controls[] = $control;
        }
        
        if($control instanceof ffFile) {
            $this->multipart = true;
        }
        
        $control->_ffPhp = $this;
        
        return $control;
    }
    
    public function GetHtml() {
    
    }
    
    public function IsSent() {
    
    }
    
    public function IsComplete() {
    
    }
    
    public function ApplySent() {
    
    }
    
    private function SetRequestArray() {
        if(stripos($this->method, 'get') !== false)
            $this->req =& $_GET;
        else
            $this->req =& $_POST;
    }
    
    
}
