<?php

class ffRadio extends ffObject implements ffiControl {
    protected $allowedProperties = array('id'       => array('type' => 'string'),
                                         'label'    => array('type' => 'string'),
                                         'description' => array('type' => 'string', 'default' => ''),
                                         'error'    => array('type' => 'string',  'default' => ''),
                                         'ffPhp'    => array('type' => 'object'));
    private $choices = array();
    
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
        $this->EnsureOneChecked();
        $r = '';
        
        $r .= '<fieldset';
        
        if(isset($this->error))
            $r .= ' class="ffphp-error"';
        
        $r .= '>'.LF.'<legend>'.$this->HSC($this->label).'</legend>'.LF;
        
        if($this->error)
            $r .= '<em class="ffphp-error">'.$this->HSC($this->error).'</em>'.LF;
        
        $count = 1;
        foreach($this->choices AS $choice => $values) {
            $id = $this->id.'-'.$count++;
            
            $r .= '<label for="'.$id.'">'.LF.'<input type="radio" name="'.
                  $this->id.'" id="'.$id.'" value="'.$this->HSC($values[0]).'"';
            
            if(isset($values[1]))
                $r .= SP.$this->FlagsToHtml(array($values[1]));
            
            $r .= ' />'.LF.$this->HSC($choice).LF.'</label>'.LF;
        }
        
        if(isset($this->description))
            $r .= '<p class="desc">'.$this->description.'</p>'.LF;
        
        $r .= '</fieldset>'.LF;
        
        return $r;
    }
    
    public function IsComplete() {
        return true;
    }
    
    public function ApplySent() {
        $value = $this->GetValue();
        
        foreach($this->choices AS &$choice) {
            if($choice[0] == $value)
                $choice[1] = 'checked';
            else if(isset($choice[1]) && $choice[1] == 'checked')
                unset($choice[1]);
        } unset($choice);
    }
    
    public function GetValue($default = '') {
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
    
    public function CheckChoice($newChoice) {
        if(isset($this->choices[$newChoice])) {
            foreach($this->choices AS &$choice) {
                if(isset($choice[1]) && $choice[1] == 'checked')
                    unset($choice[1]);
            }unset($choice);
        
            $this->choices[$newChoice][1] = 'checked';
        }
    }
    
    public function DisableChoices($choice) {
        foreach(func_get_args() AS $argument) {
            if(isset($this->choices[$argument]))
                $this->choices[$argument][1] = 'disabled';
        }
    }
    
    private function EnsureOneChecked() {
        $found = false;
        foreach($this->choices AS $choice) {
            if(isset($choice[1]) && $choice[1] == 'checked')
                $found = true;
        }
        if($found)
            return;
        
        foreach($this->choices AS &$choice) {
            if(isset($choice[1]) && $choice[1] == 'disabled')
                continue;
            
            $choice[1] = 'checked';
            return;
        } unset($choice);
    }
}
