<?php

class ffInput extends ffObject implements ffiControl{
    protected $allowedProperties = array('id'       => array('type' => 'string'),
                                         'label'    => array('type' => 'string'),
                                         'lines'    => array('type' => 'u+int', 'default' => 1),
                                         'cols'     => array('type' => 'u+int', 'default' => 15),
                                         'maxlength'=> array('type' => 'uint', 'default' => -1),
                                         'password' => array('type' => 'bool', 'default' => false),
                                         'value'    => array('type' => 'string', 'default' => ''),
                                         'flags'    => array('type' => 'array', 'default' => array()),
                                         'cssClass' => array('type' => 'array',  'default' => array()),
                                         'required' => array('type' => 'bool',  'default' => false),
                                         'error'    => array('type' => 'string',  'default' => ''),
                                         'ffPhp'   => array('type' => 'object'));
    
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
        
    }
    
    public function ApplySent() {

    }
}
