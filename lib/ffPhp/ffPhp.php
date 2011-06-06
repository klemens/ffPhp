<?php

if(!defined('LF')) define('LF', "\n");
if(!defined('SP')) define('SP', ' ');

class ffException extends exception {};

require_once 'ffObject.php';
require_once 'container/ffChoiceContainer.php';

require_once 'controls/ffFieldset.php';
require_once 'controls/ffInput.php';
require_once 'controls/ffHidden.php';
require_once 'controls/ffButton.php';
require_once 'controls/ffCheckbox.php';
require_once 'controls/ffRadio.php';
require_once 'controls/ffList.php';

class ffPhp extends ffObject {
    protected $allowedProperties = array('id'       => array('type' => 'u+int'),
                                         'action'   => array('type' => 'string', 'default' => ''),
                                         'method'   => array('type' => 'string', 'callback' => 'SetRequestArray'),
                                         'oddHighlight' => array('type' => 'bool', 'default' => false),
                                         'cssClass' => array('type' => 'array',  'default' => array()));
    public $req;
    
    private $multipart = false;
    private $hiddenSentFieldAdded = false;
    
    private $controls = array();
    private $hiddenControls = array();
    private $buttons = array();
    
    public function __construct($method = 'post') {
        $this->method = $method;
        
        $this->action = $_SERVER['SCRIPT_NAME'];
        
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
        
        $control->ffPhp = $this;
        
        return $control;
    }
    
    public function GetHtml() {
        $r = '';
        
        if(empty($this->controls) && empty($this->buttons))
            throw new exception('You must add at least one visible element.');
        
        if(!$this->hiddenSentFieldAdded) {
            $this->Add(new ffHidden('ffPhpFormSent'))->value = (string)$this->id;
        }
        
        if($this->multipart)
            $this->method = 'post';
        
        if($this->multipart)
            $r .= '<form enctype="multipart/form-data" ';
        else
            $r .= '<form ';
        
        $r .= sprintf(' accept-charset="UTF-8" method="%s" action="%s" class="%s">'.LF,
                      $this->method, $this->action,
                      implode(' ', array_merge((array)'ffphp', $this->cssClass)));
        
        $fieldsetOpen = false;
        $highlightCurrentRow = false;
        foreach($this->controls AS &$control) {
            if($control instanceof ffFieldset) {
                $control->fieldsetOpen = $fieldsetOpen;
                $r .= $control->GetHtml();
                $fieldsetOpen = true;
            } else {
                if($this->oddHighlight) {
                    if($highlightCurrentRow) {
                        $r .= '<li class="ffphp-r2">'.LF;
                    } else {
                        $r .= '<li class="ffphp-r1">'.LF;
                    }
                    $highlightCurrentRow = !$highlightCurrentRow;
                } else {
                    $r .= '<li>'.LF;
                }
                $r .= $control->GetHtml();
                $r .= '</li>'.LF;
            }
        } unset($control);
        
        $r .= '</ol>'.LF.'</fieldset>'.LF;
        
        foreach($this->buttons AS &$button) {
            $r .= $button->GetHtml();
        } unset($button);
        
        $r .= '<div>'.LF;
        foreach($this->hiddenControls AS &$hiddenControl) {
            $r .= $hiddenControl->GetHtml();
        } unset($hiddenControl);
        $r .= '</div>'.LF;
        
        $r .= '</form>';
        
        return $r;
    }
    
    public function IsSent() {
        return isset($this->req['ffPhpFormSent']) && $this->req['ffPhpFormSent'] == (string)$this->id;
    }
    
    public function IsComplete() {
        $r = true;
        foreach($this->controls AS &$control) {
            if(!$control->IsComplete()) {
                $r = false;
            }
        } unset($control);
        
        return $r;
    }
    
    public function ApplySent() {
        foreach($this->controls AS &$control) {
            $control->ApplySent();
        } unset($control);
        
        foreach($this->hiddenControls AS &$hiddenControl) {
            $hiddenControl->ApplySent();
        } unset($hiddenControl);
        
        return true;
    }
    
    protected function SetRequestArray() {
        if(stripos($this->method, 'get') !== false)
            $this->req =& $_GET;
        else
            $this->req =& $_POST;
    }
}
