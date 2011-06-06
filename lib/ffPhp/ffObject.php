<?php

interface ffiControl {};
interface ffiHiddenControl extends ffiControl {};
interface ffiButtonControl extends ffiControl {};

abstract class ffObject {
    /**
     * This function has to return the html code of the object.
     * It may be called several times!
     * @throws ffException
     */
    abstract public function GetHtml();
    
    /**
     * This function has to check, if all required information was submitted
     * by the form, and return true if so or false if not.
     * @throws ffException
     */
    abstract public function IsComplete();
    
    /**
     * Has to assign the data that was submitted by the form as default value
     * to the object for its next display.
     * @throws ffException
     */
    abstract public function ApplySent();
    
    /**
     * An array with the allowed properties for the derived class:
     *   array('id' => array('type'     => 'uint',
     *                       'default'  => 0,
     *                       'callback' => 'OnSetId'),
     *         ...)
     * This has to be overwritten in derived classes to use properties.
     * All the type, the default and the callback value are optional!
     * Supported Types are:
     *  int
     *  uint (int greater 0 or 0)
     *  u+int (int greater 0)
     *  array
     *  string
     *  float
     *  bool
     *  object
     * The callback function is called after the property has been set.
     * It has to be non-static, can be private and doesn't take arguments.
     * It can be used to verify the property. (throw ffException if invalid)
     */
    protected $allowedProperties;
    
    /**
     * The properties itself as an associative array.
     */
    protected $properties;
    
    /**
     * Magic function to set the properties defined in $allowedProperties.
     * @throws ffException
     */
    public function __set($name, $value) {
        if(!isset($this->allowedProperties[$name])) {
            throw new ffException('No such property "'.$name.'"!');
        }
        
        if(!isset($this->allowedProperties[$name]['type'])) {
            $this->properties[$name] = $value;
        } else {
            switch($this->allowedProperties[$name]['type']) {
                case 'int':
                    if(!is_int($value))
                        throw new ffException('"'.$name.'" has to be an int!');
                    $this->properties[$name] = $value;
                    break;
                case 'uint':
                    if(!is_int($value) || $value < 0)
                        throw new ffException('"'.$name.'" has to be an unsigned int!');
                    $this->properties[$name] = $value;
                    break;
                case 'u+int':
                    if(!is_int($value) || $value < 1)
                        throw new ffException('"'.$name.'" has to be an unsigned int greater than zero!');
                    $this->properties[$name] = $value;
                    break;
                case 'array':
                    if(!is_array($value))
                        throw new ffException('"'.$name.'" has to be an array!');
                    $this->properties[$name] = $value;
                    break;
                case 'string':
                    if(!is_string($value))
                        throw new ffException('"'.$name.'" has to be a string!');
                    $this->properties[$name] = $value;
                    break;
                case 'float':
                    if(!is_float($value))
                        throw new ffException('"'.$name.'" has to be a float!');
                    $this->properties[$name] = $value;
                    break;
                case 'bool':
                    if(!is_bool($value))
                        throw new ffException('"'.$name.'" has to be a bool!');
                    $this->properties[$name] = $value;
                    break;
                case 'object':
                    if(!is_object($value))
                        throw new ffException('"'.$name.'" has to be an object!');
                    $this->properties[$name] = $value;
                    break;
                default:
                    throw new ffException('Wrong type definition in object: There is no "'.$this->allowedProperties[$name]['type'].'"!');
            }
        }
        
        if(isset($this->allowedProperties[$name]['callback'])) {
            if(is_callable(array($this, $this->allowedProperties[$name]['callback']))) {
                call_user_func(array($this, $this->allowedProperties[$name]['callback']));
            } else {
                throw new ffException('Callback function of "'.$name.'" does not exist!');
            }
        }
    }
    
    /**
     * Magic function to get the properties stored in $properties.
     * @throws ffException
     */
    public function __get($name) {
        if(isset($this->properties[$name])) {
            return $this->properties[$name];
        } else if(isset($this->allowedProperties[$name]['default'])) {
            return $this->allowedProperties[$name]['default'];
        } else {
            throw new ffException('No such property "'.$name.'"!');
        }
    }
    
    /**
     * Magic function to check if a property is set.
     */
    public function __isset($name) {
        return isset($this->properties[$name]);
    }
    
    /**
     * Magic function to unset a property.
     */
    public function __unset($name) {
        if(isset($this->properties[$name]))
            unset($this->properties[$name]);
    }
    
    /**
     * Checks if all required properties are set.
     * (Required properties don't have a default value)
     * @throws ffException
     */
    protected function CheckProperties() {
        foreach($this->allowedProperties AS $property => $propertyValues) {
            if(!isset($propertyValues['default']) && !isset($this->properties[$property])) {
                if(isset($propertyValues['type']))
                    throw new ffException('Required property "'.$property.'" of type "'.$propertyValues['type'].'" not set!');
                else
                    throw new ffException('Required property "'.$property.'" not set!');
            }
        }
    }
    
    /**
     * Function to show the object. Simply calls GetHtml() internally.
     * @see GetHtml()
     * @throws ffException
     */
    public function Show() {
        echo $this->GetHtml();
    }
    
    /**
     * Alias for htmlspecialchars().
     */
    public function HSC($string) {
        return htmlspecialchars($string);
    }
    
    /**
     * Returns xHtml code for an array of flags:
     * array('disabled') becomes 'disabled="disabled"'
     */
    public function FlagsToHtml($flags) {
        $r = '';

        foreach($flags AS $flag) {
            if(empty($flag))
                continue;
            
            $r .= $flag.'="'.$flag.'" ';
        }

        return trim($r);
    }
     
    /**
     * This function tries to create a valid xHtml id fron a given label.
     * If the label only exists of forbidden characters, a ffException is thrown.
     */
    public function LabelToId($label) {
        $id = preg_replace("/[^a-zA-Z0-9\\-_:\\.]/", '', $label);
        $id = trim($id);
        if(preg_match("/^[a-zA-Z]{1}.*/", $id))
            return $id;
        else
            throw new ffException('Specify an id or use a label that is convertible to an id!');
    }
}
