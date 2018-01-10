<?php

namespace Framework\Service
{
    use Framework\Base as Base;
    use Framework\Registry as Registry;
    use Framework\ArrayMethods as ArrayMethods;
    use Framework\StringMethods as StringMethods;
    use Framework\Service\Exception as Exception;
    
    class Query extends Base
    {
        /**
        * @readwrite
        */
        protected $_connector;
        
        /**
        * @read
        */
        protected $_from;
        
        /**
        * @read
        */
        protected $_fields;
        
        /**
        * @read
        */
        protected $_limit;
        
        /**
        * @read
        */
        protected $_offset;
        
        /**
        * @read
        */
        protected $_order;
        
        /**
        * @read
        */
        protected $_direction;
        
        /**
        * @read
        */
        protected $_join = array();
        
        /**
        * @read
        */
        protected $_where = array();
        
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
                    
        protected function _quote($value)
        {
            if (is_string($value))
            {
                $escaped = StringMethods::escape($value);
                return '"'.$escaped.'"';
            }
            
            if (is_array($value))
            {
                $buffer = array();
                
                foreach ($value as $i)
                {
                    array_push($buffer, $this->_quote($i));
                }
        
                $buffer = join(", ", $buffer);
                return '"'.$buffer.'"';
            }
            
            if (is_null($value))
            {
                return "NULL";
            }
            
            if (is_bool($value))
            {
                return (int) $value;
            }
            
            return null;
        }
        
        public function from($from, $fields = array("*"))
        {
            if (empty($from))
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $this->_from = $from;
            
            if ($fields)
            {
                $this->_fields[$from] = $fields;
            }
            
            return $this;
        }
        
        public function limit($limit, $offset = 0)
        {
            if (empty($limit))
            {
                throw new Exception\Argument("Invalid argument");
            }

            $this->_limit = $limit;
            $this->_offset = $offset;
            
            return $this;
        }
        
        public function order($order, $direction = "asc")
        {
            if (empty($order))
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $this->_order = $order;
            $this->_direction = $direction;
            
            return $this;
        }
        
        public function where()
        {
            $arguments = func_get_args();
            
            if (sizeof($arguments) < 1)
            {
                throw new Exception\Argument("Invalid argument");
            }
            
            $this->_where[$arguments[0]] = $arguments[1];
            
            return $this;
        }
        
    }
}