<?php

/**
 * A container to store multiple choices.
 *
 * This Container can store multiple choices of which each has the attributes
 * enabled and selected. Each of them can be set or removed seperately.
 * Two choices must not have the same name thus adding a choice with a name that
 * is already in the container will override the existing choice.
 * Additionally each choice can have a value. The name will be used as the value
 * by default.
 */
class ffChoiceContainer implements IteratorAggregate {
    /**
     * Contains the choices:
     * name => array(value => (str), selected => (bool), enabled => (bool))
     */
    private $choices = array();
    
    /**
     * Adds the given choices to the container.
     *
     * This function takes an arbitrary number of choices that will be added to
     * the container.
     * Example:
     * <code>
     * <?php
     * $group = new ffChoiceContainer();
     * $group->Add('Choice 1', array('Choice 2', 'value for choice 2'));
     * ?>
     * </code>
     *
     * @param ... Arbitrary number of choices
     * @see AddArray
     */
    public function Add() {
        $choices = func_get_args();
        $this->AddArray($choices);
    }
    
    /**
     * Adds the given choices to the container.
     *
     * $choices is a single-dimensional array of choices that will be added to
     * the container. 
     * A choice is either a string that will be used both as the choice name
     * and its value or an array than contains the choice name at index 0 and
     * its value at index 1. String and array choices can be added together.
     * Choices are enabled and not selected by default.
     * Example:
     * <code>
     * <?php
     * $group = new ffChoiceContainer();
     * $group->AddArray(array('Choice 1',
     *                        'Choice 2',
     *                        array('Choice 3', 'value for choice 3'),
     *                        'Choice 4'));
     * ?>
     * </code>
     *
     * @param array $choices Array of choices
     */
    public function AddArray(array &$choices) {
        foreach($choices AS &$argument) {
            if(is_array($argument)) {
                if(count($argument) == 2 && isset($argument[0], $argument[1]))
                    $this->choices[(string)$argument[0]] = $this->MakeChoice((string)$argument[1]);
                else
                    throw new ffException('The argument has to be a string or a array like: array(name, value)!');
            } else {
                $this->choices[(string)$argument] = $this->MakeChoice((string)$argument);
            }
        } unset($argument);
    }
    
    /**
     * Deletes the given choices from the container.
     *
     * Example:
     * <?php
     * $group = new ffChoiceContainer();
     * $group->Add('Choice 1', array('Choice 2', 'value for choice 2'));
     * $group->Delete('Choice 1');
     * ?>
     *
     * @param ... Arbitrary number of choices
     * @see DeleteArray
     * @see Add
     */
    public function Delete() {
        $choices = func_get_args();
        $this->AddArray($choices);
    }
    
    /**
     * Deletes the given choices from the container.
     *
     * Deletes the given choices from the container. Each choice has to be a
     * string. Example:
     * <code>
     * <?php
     * $group = new ffChoiceContainer();
     * $group->AddArray(array('Choice 1',
     *                        'Choice 2',
     *                        array('Choice 3', 'value for choice 3'),
     *                        'Choice 4'));
     * $group->DeleteArray(array('Choice 1', 'Choice 3'));
     * ?>
     * </code>
     *
     * @param array $choices Array of choices
     */
    public function DeleteArray(array &$choices) {
        foreach($choices AS &$argument) {
            if(isset($this->choices[$argument]))
                unset($this->choices[$argument]);
        } unset($argument);
    }
    
    /**
     * Sets the selected state of the given choices in the container.
     *
     * @param ... Arbitrary number of choices
     * @see Delete
     */
    public function Select() {
        $choices = func_get_args();
        $this->SetSelected($choices, true);
    }
    
    /**
     * Sets the selected state of the given choices in the container.
     *
     * @param array $choices Array of choices
     * @see DeleteArray
     */
    public function SelectArray(array &$choices) {
        $this->SetSelected($choices, true);
    }
    
    /**
     * Sets the selected state of the choices which have the given values.
     *
     * @param ... Arbitrary number of values
     * @see Delete
     */
    public function SelectByValue() {
        $values = func_get_args();
        $this->SetSelectedByValue($values, true);
    }
    
    /**
     * Sets the selected state of the choices which have the given values.
     *
     * @param array $choices Array of choices
     * @see DeleteArray
     */
    public function SelectByValueArray(array &$values) {
        $this->SetSelectedByValue($values, true);
    }
    
    /**
     * Removes the selected state of the given choices in the container.
     *
     * @param ... Arbitrary number of choices
     * @see Delete
     */
    public function Unselect() {
        $choices = func_get_args();
        $this->SetSelected($choices, false);
    }
    
    /**
     * Removes the selected state of the given choices in the container.
     *
     * @param array $choices Array of choices
     * @see DeleteArray
     */
    public function UnselectArray(array &$choices) {
        $this->SetSelected($choices, false);
    }
    
    /**
     * Sets the enabled state of the given choices in the container.
     *
     * @param ... Arbitrary number of choices
     * @see Delete
     */
    public function Enable() {
        $choices = func_get_args();
        $this->SetEnabled($choices, true);
    }
    
    /**
     * Sets the enabled state of the given choices in the container.
     *
     * @param array $choices Array of choices
     * @see DeleteArray
     */
    public function EnableArray(array &$choices) {
        $this->SetEnabled($choices, true);
    }
    
    /**
     * Removes the enabled state of the given choices in the container.
     *
     * @param ... Arbitrary number of choices
     * @see Delete
     */
    public function Disable() {
        $choices = func_get_args();
        $this->SetEnabled($choices, false);
    }
    
    /**
     * Removes the enabled state of the given choices in the container.
     *
     * @param array $choices Array of choices
     * @see DeleteArray
     */
    public function DisableArray(array &$choices) {
        $this->SetEnabled($choices, false);
    }
    
    /**
     * Set the value of the given choice.
     *
     * @param string $choice The choice whose value you want to set
     * @param string $value The value to assing to the choice
     */
    public function SetValue($choice, $value) {
        if(isset($this->choices[$choice]))
            $this->choices[$choice]->value = $value;
    }
    
    /**
     * Deletes all choices.
     */
    public function DeleteAll() {
        $this->choices = array();
    }
    
    /**
     * Unselects all choices.
     */
    public function UnselectAll() {
        foreach($this->choices AS &$choice) {
            $choice->selected = false;
        } unset($choice);
    }
    
    /**
     * Tries to ensure that at least one choice is selected.
     *
     * Tries to select the first choice that is enabled if no other choice is
     * selected.
     * This fails if there are no choices of if all choices are disabled.
     *
     * @return bool true if at least one choice is selected, false otherwise
     */
    public function EnsureOneSelected() {
        if($this->NumSelected() > 0)
            return true;
        
        foreach($this->choices AS &$choice) {
            if(!$choice->enabled)
                continue;
            
            $choice->checked = true;
            return true;
        } unset($choice);
        
        return false;
    }
    
    /**
     * Returns the number of choices that are selected.
     *
     * @return int The number of choices selected
     */
    public function NumSelected() {
        return $this->Count('selected');
    }
    
    /**
     * Returns the number of choices that are enabled.
     *
     * @return int The number of choices enabled
     */
    public function NumEnabled() {
        return $this->Count('enabled');
    }
    
    /**
     * Returns the number of choices.
     *
     * @return int The number of choices
     */
    public function Num() {
        return count($this->choices);
    }
    
    /**
     * Returns an ArrayIterator with all the choices.
     *
     * This function allows to iterate over the choices with foreach:
     * <code>
     * $choices = new ffChoiceContainer();
     * $choices->Add('Choice 1', 'Choice 2');
     *
     * foreach($choices AS $name => $options) {
     *     echo $name;
     *     echo 'Value:'.$options->value;
     *     echo 'Selected:'.($options->value ? 'Yes' : 'No');
     *     echo 'Enabled:'.($options->enabled ? 'Yes' : 'No');
     * }
     * </code>
     *
     * @return ArrayIterator Iterator for use in foreach
     */
    public function getIterator() {
        return new ArrayIterator($this->choices);
    }
    
    /**
     * Creates a choice object  with the given value.
     */
    private function MakeChoice($value) {
        return (object) array('value' => $value, 'enabled' => true, 'selected' => false);
    }
    
    /**
     * Sets the selected $state to the array of $choices.
     */
    private function SetSelected(array &$choices, $state) {
        foreach($choices AS &$choice) {
            if(isset($this->choices[$choice])) {
                $this->choices[$choice]->selected = $state;
                if($state && !$this->choices[$choice]->enabled)
                    $this->choices[$choice]->enabled = true;
            }
        } unset($choice);
    }
    
    /**
     * Sets the selected $state to the array of $choices.
     */
    private function SetSelectedByValue(array &$values, $state) {
        foreach($this->choices AS &$choice) {
            if(in_array($choice->value, $values)) {
                $choice->selected = $state;
                if($state && !$choice->enabled)
                    $choice->enabled = true;
            }
        } unset($choice);
    }
    
    /**
     * Sets the enabled $state to the array of $choices.
     */
    private function SetEnabled(array &$choices, $state) {
        foreach($choices AS &$choice) {
            if(isset($this->choices[$choice])) {
                $this->choices[$choice]->enabled = $state;
                if(!$state && $this->choices[$choice]->selected)
                    $this->choices[$choice]->selected = false;
            }
        } unset($choice);
    }
    
    /**
     * Counts the choices with either selected or enabled state set to true
     * depending on $type being 'selected' or 'enabled'.
     */
    private function Count($type) {
        $selected = 0;
        $enabled = 0;
        foreach($this->choices AS &$choice) {
            if($choice->selected)
                ++$selected;
            if($choice->enabled)
                ++$enabled;
        } unset($choice);
        
        switch($type) {
            case 'selected': return $selected;
            case 'enabled': return $enabled;
        }
    }
}