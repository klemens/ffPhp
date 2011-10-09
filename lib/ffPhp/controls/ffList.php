<?php

class ffList extends ffObject implements ffiControl {
    protected $allowedProperties = array('id'       => array('type' => 'string'),
                                         'label'    => array('type' => 'string'),
                                         'description' => array('type' => 'string', 'default' => ''),
                                         'size'     => array('type' => 'u+int', 'default' => 1),
                                         'error'    => array('type' => 'string',  'default' => ''),
                                         'required' => array('type' => 'bool', 'default' => false),
                                         'flags'    => array('type' => 'array', 'default' => array()),
                                         'multiple' => array('type' => 'bool', 'default' => false),
                                         'choices'  => array('type' => 'object'),
                                         'ffPhp'    => array('type' => 'object'));
    private $mode;
    private $choiceGroups = array();
    
    public function __construct($label = null, $id = null) {
        if(isset($label))
            $this->label = $label;
        
        if(isset($id))
            $this->id = $id;
        else if(isset($label))
            $this->id = $this->LabelToId($label);
        
        $this->choices = new ffChoiceContainer;
        $this->mode = 'single';
    }
    
    public function GetHtml() {
        $this->CheckProperties();
        $this->CheckSelectedCount();
        $r = '';
        
        $r .= '<label for="'.$this->id.'">'.$this->HSC($this->label);
        
        if(isset($this->required))
            $r .= ' <em title="Required field!">*</em>';
        
        $r .= '</label>'.LF;
        
        $r .= '<div class="item">'.LF;

        $r .= '<select id="'.$this->id.'" name="'.$this->id.'[]"';
        
        $r .= ' size="'.$this->size.'"';
        
        if($this->multiple)
            $r .= ' multiple="multiple"';
        
        if(isset($this->flags))
            $r .= SP.$this->FlagsToHtml($this->flags);
        
        if(isset($this->error))
            $r .= ' class="ffphp-error"';
        
        $r .= '>'.LF;
        
        if($this->mode == 'group') {
            foreach($this->choiceGroups AS $groupName => $group) {
                $r .= '<optgroup label="'.$this->HSC($groupName).'">'.LF;
                
                $r .= $this->GetHtmlGroup($group);
                
                $r .= '</optgroup>'.LF;
            }
        } else {
            $r .= $this->GetHtmlGroup($this->choices);
        }
        
        $r .= '</select>'.LF;
        
        if($this->error)
            $r .= '<em class="ffphp-error">'.$this->HSC($this->error).'</em>'.LF;
        
        if(isset($this->description))
            $r .= '<p class="desc">'.$this->HSC($this->description).'</p>'.LF;
        
        $r .= '</div>'.LF;
        
        return $r;
    }
    
    private function GetHtmlGroup($group) {
        $r = '';
        
        foreach($group AS $name => $options) {
            $r .= '<option value="'.$this->HSC($options->value).'"';
            
            if($options->selected)
                $r .= ' selected="selected"';
            else if(!$options->enabled)
                $r .= ' disabled="disabled"';
            
            $r .= '>'.$this->HSC($name).'</option>'.LF;
        }
        
        return $r;
    }
    
    public function IsComplete() {
        if($this->required && 0 === count($this->GetValue())) {
            $this->error = '';
            return false;
        }
        
        return true;
    }
    
    public function ApplySent() {
        $values = $this->GetValue();
        
        if(!$this->multiple && count($values) > 1)
            throw new ffException('Cannot apply more than one choice unless multiple set to true');
        
        if($this->mode == 'group') {
            foreach($this->choiceGroups AS &$group) {
                $group->UnselectAll();
                $group->SelectByValueArray($values);
            } unset($group);
        } else { /////// ADD ffChoiceContainer::SelectByValue()!!
            $this->choices->UnselectAll();
            $this->choices->SelectByValueArray($values);
        }
    }
    
    public function GetValue($default = array()) {
        if(!empty($this->ffPhp->req[$this->id]))
            return $this->ffPhp->req[$this->id];
        else
            return $default;
        
    }
    
    public function GetSingleValue() {
        $value = $this->GetValue();
        
        if(!$this->multiple) {
            if(count($value))
                return $value[0];
            else
                return '';
        } else {
            throw new ffException('You can only use this method when multiple is false. Use GetValue instead.');
        }
    }
    
    public function AddGroup($name) {
        $this->mode = 'group';
        
        if(isset($this->choiceGroups[$name]))
            throw new ffException('This group already exits!');
        
        $this->choiceGroups[$name] = new ffChoiceContainer;
        
        return $this->choiceGroups[$name];
    }
    
    private function CheckSelectedCount() {
        if(!$this->multiple) {
            $count = 0;
            if($this->mode == 'group') {
                foreach($this->choiceGroups AS $group) {
                    $count += $group->NumSelected();
                }
            } else {
                $count += $this->choices->NumSelected();
            }
            
            if($count > 1)
                throw new ffException('Unless multiple set to true you must not select more the one choice!');
        }
    }
}
