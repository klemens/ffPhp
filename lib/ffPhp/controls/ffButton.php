<?php

class ffButton extends ffObject implements ffiButtonControl {
    protected $allowedProperties = array('id'      => array('type' => 'string', 'default' => ''),
                                         'type'    => array('type' => 'string', 'default' => 'submit', 'callback' => 'OnType'),
                                         'label'   => array('type' => 'string'),
                                         'description' => array('type' => 'string', 'default' => ''),
                                         'value'   => array('type' => 'string', 'default' => ''),
                                         'flags'   => array('type' => 'array', 'default' => array()),
                                         'ffPhp'   => array('type' => 'object'));
    
    public function __construct($label = null) {
        if($label)
            $this->label = $label;
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        $r = '';
        
        $r .= '<fieldset class="ffphp-button">'.LF.'<ol>'.LF.'<li>'.LF.
              '<button';
        
        if(isset($this->id))
            $r .= 'id="'.$this->id.'" name="'.$this->id.'"';
        
        $r .= ' type="'.$this->type.'"';
        
        if(isset($this->value)) {
            $r .= ' value="'.$this->value.'"';
        }
        
        if(isset($this->flags)) {
            $r .= SP.$this->FlagsToHtml($this->flags);
        }
        
        $r .= '>'.$this->label.'</button>';
        
        if(isset($this->description))
            $r .= '<p class="desc">'.$this->description.'</p>'.LF;
        
        $r .= '</li>'.LF.'</ol>'.LF.'</fieldset>'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        return true;
    }
    
    public function ApplySent() {
        if(!empty($this->ffPhp->req[$this->id]) && empty($this->value))
            $this->value = $this->ffPhp->req[$this->id];
    }
    
    protected function OnType() {
        $type = trim(strtolower($this->type));
        if('reset' == $type)
            $this->type = 'reset';
        else
            $this->type = 'submit';
    }
}
