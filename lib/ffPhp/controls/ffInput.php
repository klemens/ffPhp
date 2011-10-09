<?php

class ffInput extends ffObject implements ffiControl{
    protected $allowedProperties = array('id'       => array('type' => 'string'),
                                         'label'    => array('type' => 'string'),
                                         'description' => array('type' => 'string', 'default' => ''),
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
    
    public function __construct($label = null, $id = null) {
        if(isset($label))
            $this->label = $label;
        
        if(isset($id))
            $this->id = $id;
        else if(isset($label))
            $this->id = $this->LabelToId($label);
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        $r = '';
        
        $r .= '<label for="'.$this->id.'">'.$this->HSC($this->label);
        
        if(isset($this->required))
            $r .= ' <em title="Required field!">*</em>';
        
        $r .= '</label>'.LF;
        $r .= '<div class="item">'.LF;
        
        if($this->lines > 1) { //textarea
            $r .= '<textarea id="'.$this->id.'" name="'.$this->id.'"'.
                  ' cols="'.$this->cols.'" rows="'.$this->lines.'"';
            
            if(isset($this->flags))
                $r .= SP.$this->FlagsToHtml($this->flags);
            
            if(isset($this->error))
                $r .= ' class="ffphp-error"';
            
            $r .= '>';
            
            if(isset($this->value))
                $r .= $this->HSC($this->value);
            
            $r .= '</textarea>'.LF;
        } else { //input
            $r .= '<input id="'.$this->id.'" name="'.$this->id.'"';
            
            if($this->password)
                $r .= ' type="password"';
            else
                $r .= ' type="text"';
            
            if(isset($this->value))
                $r .= ' value="'.$this->HSC($this->value).'"';
            
            if(isset($this->maxlength))
                $r .= ' maxlength="'.$this->maxlength.'"';
            
            if(isset($this->flags))
                $r .= SP.$this->FlagsToHtml($this->flags);
            
            if(isset($this->error))
                $r .= ' class="ffphp-error"';
            
            $r .= ' />'.LF;
        }
        
        if($this->error)
            $r .= '<em class="ffphp-error">'.$this->HSC($this->error).'</em>'.LF;
        
        if(isset($this->description))
            $r .= '<p class="desc">'.$this->HSC($this->description).'</p>'.LF;
        
        $r .= '</div>'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        if($this->required && empty($this->ffPhp->req[$this->id])) {
            $this->error = '';
            return false;
        }
        
        if(!empty($this->ffPhp->req[$this->id]) && $this->regex &&
           !preg_match($this->regex, $this->ffPhp->req[$this->id])) {
            $this->error = '';
            return false;
        }
        
        return true;
    }
    
    public function ApplySent() {
        if(!$this->password && !empty($this->ffPhp->req[$this->id]) && empty($this->value)) {
            $this->value = $this->ffPhp->req[$this->id];
        }
    }
    
    public function GetValue($default = '') {
        if(!empty($this->ffPhp->req[$this->id]))
            return $this->ffPhp->req[$this->id];
        else
            return $default;
    }
}
