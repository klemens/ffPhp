<?php

class ffFieldset extends ffObject implements ffiControl{
    protected $allowedProperties = array('id'       => array('type' => 'string', 'default' => ''),
                                         'label'    => array('type' => 'string', 'default' => ''),
                                         'cssClass' => array('type' => 'array',  'default' => array()),
                                         'ffPhp'    => array('type' => 'object'));
    
    public $fieldsetOpen = false;
    
    public function __construct($label = null) {
        if($label)
            $this->label = $label;
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        $r = '';
        
        if($this->fieldsetOpen) {
            $r .= '</ol>'.LF.'</fieldset>'.LF;
        }
        
        $r .= '<fieldset';
        
        if(isset($this->cssClass)) {
            $r .= ' class="'.implode(' ', $this->cssClass).'"';
        }
        
        if(isset($this->id)) {
            $r .= ' id="'.$this->id.'"';
        }
        
        $r .= '>'.LF;
        
        if(isset($this->label)) {
            $r .= '<legend>'.$this->label.'</legend>'.LF;
        }
        
        $r .= '<ol>'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        return true;
    }
    
    public function ApplySent() {
        return true;
    }
}
