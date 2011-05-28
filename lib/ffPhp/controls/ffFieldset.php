<?php

class ffFieldset extends ffObject implements ffiControl{
    protected $allowedProperties = array('id'       => array('type' => 'string', 'default' => ''),
                                         'legend'   => array('type' => 'string', 'default' => ''),
                                         'cssClass' => array('type' => 'array',  'default' => array()),
                                         'ffPhp'   => array('type' => 'object'));
    
    public $fieldsetOpen = false;
    
    public function __construct($legend = null) {
        if($legend)
            $this->legend = $legend;
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
        
        if(isset($this->legend)) {
            $r .= '<legend>'.$this->legend.'</legend>'.LF;
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
