<?php

class ffCheckbox extends ffObject implements ffiControl {
    protected $allowedProperties = array('id'       => array('type' => 'string'),
                                         'label'    => array('type' => 'string'),
                                         'error'    => array('type' => 'string',  'default' => ''),
                                         'ffPhp'    => array('type' => 'object'));
    private $choices = array();
    
    public function __construct($id = null, $label = null) {
        if(isset($id))
            $this->id = $id;
        
        if(isset($label))
            $this->label = $label;
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        $r = '';
        
        $r .= '<fieldset>'.LF.'<legend';
        
        if(isset($this->error))
            $r .= ' class="ffphp-error"';
        
        $r .= '>'.$this->HSC($this->label).'</legend>'.LF;
        
        if(isset($this->error))
            $r .= '<em class="ffphp-error">'.$this->error.'</em>'.LF;
        
        $count = 1;
        foreach($this->choices AS $choice => $values) {
            $id = $this->id.'-'.$count++;
            
            $r .= '<label for="'.$id.'">'.LF.'<input type="checkbox" name="'.
                  $this->id.'[]" id="'.$id.'" value="'.$this->HSC($values[0]).'"';
            
            if(isset($values[1]))
                $r .= SP.$this->FlagsToHtml(array($values[1]));
            
            $r .= ' />'.LF.$this->HSC($choice).LF.'</label>'.LF;
        }
        
        $r .= '</fieldset>'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        return true;
    }
    
    public function ApplySent() {
        $values = $this->GetValue();
        
        foreach($this->choices AS &$choice) {
            if(in_array($choice[0], $values))
                $choice[1] = 'checked';
            else if(isset($choice[1]) && $choice[1] == 'checked')
                unset($choice[1]);
        } unset($choice);
    }
    
    public function GetValue($default = array()) {
        if(!empty($this->ffPhp->req[$this->id]))
            return $this->ffPhp->req[$this->id];
        else
            return $default;
    }
    
    public function AddChoices() {
        foreach(func_get_args() AS $argument) {
            if(is_array($argument)) {
                if(count($argument) == 2 && isset($argument[0], $argument[1]))
                    $this->choices[(string)$argument[0]] = array((string)$argument[1]);
                else
                    throw new ffException('The argument has to be a string or a array like: array(name, value)!');
            } else {
                $this->choices[(string)$argument] = array((string)$argument);
            }
        }
    }
    
    public function ResetChoices() {
        $this->choices = array();
    }
    
    public function CheckChoices() {
        foreach(func_get_args() AS $argument) {
            if(isset($this->choices[$argument]))
                $this->choices[$argument][1] = 'checked';
        }
    }
    
    public function DisableChoices() {
        foreach(func_get_args() AS $argument) {
            if(isset($this->choices[$argument]))
                $this->choices[$argument][1] = 'disabled';
        }
    }
    
    public function IsChecked($name) {
        if(isset($this->choices[$name][0])) {
            return in_array($this->choices[$name][0], $this->GetValue());
        } else return false;
    }
}
