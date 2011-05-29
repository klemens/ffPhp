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
        $r = '';
        
        $r .= '<label for="'.$this->id.'">'.$this->HSC($this->label);
        
        if($this->required)
            $r .= ' <em title="You have to fill in this field!">*</em>';
        
        $r .= '</label>'.LF;
        
        if($this->lines > 1) { //textarea
            $r .= '<textarea id="'.$this->id.'" name="'.$this->id.'"'.
                  ' cols="'.$this->cols.'" rows="'.$this->lines.'"';
            
            if($this->flags)
                $r .= SP.$this->FlagsToHtml($this->flags);
            
            if($this->error)
                $r .= ' class="ffphp-error"';
            
            $r .= '>';
            
            if($this->value)
                $r .= $this->HSC($this->value);
            
            $r .= '</textarea>'.LF;
        } else { //input
            $r .= '<input id="'.$this->id.'" name="'.$this->id.'"';
            
            if($this->password)
                $r .= ' type="password"';
            else
                $r .= ' type="text"';
            
            if($this->value)
                $r .= ' value="'.$this->HSC($this->value).'"';
            
            if($this->maxlength)
                $r .= ' maxlength="'.$this->maxlength.'"';
            
            if($this->flags) {
                $r .= SP.$this->FlagsToHtml($this->flags);
            }
            
            if($this->error) {
                $r .= ' class="ffphp-error"';
            }
            
            $r .= ' />'.LF;
        }
        
        if($this->error)
            $r .= '<em class="ffphp-error">'.$this->HSC($this->error).'</em>'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        if($this->required && empty($this->ffPhp->req[$this->id])) {
            $this->error = 'You have to fill in this field!';
            return false;
        }
        
        if(!empty($this->ffPhp->req[$this->id]) && $this->regex &&
           !preg_match($this->regex, $this->ffPhp->req[$this->id])) {
            $this->error = 'Your input does not have the right format!';
            return false;
        }
        
        return true;
    }
    
    public function ApplySent() {
        if(!$this->password && !empty($this->ffPhp->req[$this->id]) && empty($this->value)) {
            $this->value = $this->ffPhp->req[$this->id];
        }
    }
}
