<?php

    namespace Packages\Framebone\Database\Model;
    
    use \Packages\Framebone\Database\Model\Proxy as Proxy;

    abstract class EntityAbstract
    {
        protected $_values = array();
        protected $_allowedFields = array();
       
        /**
         * Constructor
         */
        public function __construct(array $fields)
        {
            foreach ($fields as $name => $value) {
                $this->$name = $value;
            }
        }
       
        /**
         * Assign a value to the specified field via the corresponding mutator (if it exists);
         * otherwise, assign the value directly to the '$_values' protected array
         */
        public function __set($name, $value)
        {  
            if (!in_array($name, $this->_allowedFields)) {
                throw new \InvalidArgumentException('The field ' . $name . ' is not allowed for this entity.'); 
            }
            $mutator = 'set' . ucfirst($name);
            if (method_exists($this, $mutator) && is_callable(array($this, $mutator))) {
                $this->$mutator($value);
            }
            else {
                $this->_values[$name] = $value;
            }   
        }
       
        /**
         * Get the value assigned to the specified field via the corresponding getter (if it exists);
         * otherwise, get the value directly from the '$_values' protected array
         */
        public function __get($name)
        {
            if (!in_array($name, $this->_allowedFields)) {
                throw new \InvalidArgumentException('The field ' . $name . ' is not allowed for this entity.');
            }
            $accessor = 'get' . ucfirst($name);
            if (method_exists($this, $accessor) && is_callable(array($this, $accessor))) {
                return $this->$accessor;
            }
            if (!isset($this->_values[$name])) {
                throw new \InvalidArgumentException('The field ' . $name . ' has not been set for this entity yet.');
            }
            // if the field is a proxy for an entity, load the entity via its 'load()' method
            $field = $this->_values[$name];
            if ($field instanceof Proxy\EntityProxy) {
                $field = $field->load();
            }
            return $field;
        }

        /**
         * Check if the specified field has been assigned to the entity
         */
        public function __isset($name)
        {
            if (!in_array($name, $this->_allowedFields)) {
                throw new \InvalidArgumentException('The field ' . $name . ' is not allowed for this entity.');
            }
            return isset($this->_values[$name]);
        }

        /**
         * Unset the specified field from the entity
         */
        public function __unset($name)
        {
            if (!in_array($name, $this->_allowedFields)) {
                throw new \InvalidArgumentException('The field ' . $name . ' is not allowed for this entity.');
            }
            if (isset($this->_values[$name])) {
                unset($this->_values[$name]);
                return true;
            }
            return false;
        }
       
        /**
         * Get an associative array with the values assigned to the fields of the entity
         */
        public function toArray()
        {
            return $this->_values;
        }

        public function _getFields()
        {
            return $this->_allowedFields;
        }

        public function _checkString($string)
        {
            return (is_string($string) && !empty($string) || is_null($string));
        }

        public function _checkInt($int)
        {
            return (is_numeric($int) && filter_var((int)$int, FILTER_VALIDATE_INT, array('options' => array('min_range' => 0, 'max_range' => 65535))) || is_null($int));
        }

        public function _checkEmail($email)
        {
            return (is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) || $this->_checkString($email));
        }
    }