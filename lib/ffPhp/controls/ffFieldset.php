<?php

class ffFieldset extends ffObject implements ffiControl{
    protected $allowedProperties = array('id'          => array('type' => 'string', 'default' => ''),
                                         'label'       => array('type' => 'string', 'default' => ''),
                                         'collapsible' => array('type' => 'bool', 'default' => false),
                                         'collapsed'   => array('type' => 'bool', 'default' => false, 'callback' => 'OnCollapsed'),
                                         'cssClass'    => array('type' => 'array',  'default' => array()),
                                         'ffPhp'       => array('type' => 'object'));
    
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
        
        $label = $this->label;
        
        if($this->collapsible) {
            $label .= ' <span class="toggle">'.($this->collapsed ? '▲' : '▼').'</span>';
        }
        
        if(!empty($label)) {
            $r .= '<legend>'.$label.'</legend>'.LF;
        }
        
        $r .= '<ol>'.LF;
        
        return $r;
    }
    
    public function OnCollapsed() {
        if($this->collapsed)
            $this->collapsible = true;
    }
    
    public function IsComplete() {
        return true;
    }
    
    public function ApplySent() {
        return true;
    }
}
