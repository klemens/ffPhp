<?php

class ffHidden extends ffObject implements ffiHiddenControl {
    protected $allowedProperties = array('id'      => array('type' => 'string'),
                                         'value'   => array('type' => 'string', 'default' => ''),
                                         'ffPhp'   => array('type' => 'object'));
    
    public function __construct($id = null) {
        if($id)
            $this->id = $id;
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        $r = '';
        
        $r .= '<input type="hidden" id="'.$this->id.'" name="'.$this->id.'"';
        
        if($this->value)
            $r .= ' value="'.$this->HSC($this->value).'"';
        
        $r .= ' />'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        return true;
    }
    
    public function ApplySent() {
        if(!empty($this->ffPhp->req[$this->id]) && empty($this->value))
            $this->value = $this->ffPhp->req[$this->id];
    }
    
    public function GetValue($default = '') {
        if(!empty($this->ffPhp->req[$this->id]))
            return $this->ffPhp->req[$this->id];
        else
            return $default;
    }
}
