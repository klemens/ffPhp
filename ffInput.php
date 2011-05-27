<?php

class ffInput extends ffObject implements ffiControl{
    protected $allowedProperties = array('id'       => array('type' => 'string'),
                                         'label'    => array('type' => 'string'),
                                         'lines'    => array('type' => 'u+int', 'default' => 1),
                                         'cols'     => array('type' => 'u+int', 'default' => 15),
                                         'maxlength'=> array('type' => 'uint', 'default' => 0),
                                         'password' => array('type' => 'bool', 'default' => false),
                                         'value'    => array('type' => 'string', 'default' => ''),
                                         'flags'    => array('type' => 'array', 'default' => array()),
                                         'regex'    => array('type' => 'string', 'default' => ''),
                                         'cssClass' => array('type' => 'array',  'default' => array()),
                                         'required' => array('type' => 'bool',  'default' => false),
                                         'error'    => array('type' => 'string',  'default' => ''),
                                         'ffPhp'    => array('type' => 'object'));
    
    public function __construct($id = null, $label = null) {
        if(isset($id))
            $this->id = $id;
        
        if(isset($label))
            $this->label = $label;
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        
        
    }
    
    public function IsComplete() {
        if($this->required && empty($this->ffPhp->req[$this->id])) {
            $this->error = 'You have to fill in this field!';
            return false;
        }
        
        if(!empty($this->ffPhp->req[$this->name]) && $this->regex &&
           !preg_match($this->regex, $this->ffPhp->req[$this->name])) {
            $this->error = 'Your input does not have the right format!'
            return false;
        }
        
        return true;
    }
    
    public function ApplySent() {
        if(!$this->password && !empty($this->ffPhp->req[$this->name]) && empty($this->value)) {
            $this->value = $this->ffPhp->req[$this->name];
        }
    }
}
