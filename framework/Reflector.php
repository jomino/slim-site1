<?php

namespace Framework
{
    class Reflector
    {

        private $_count = 0;

        public function __construct($options = array())
        {
            if (is_array($options) || is_object($options))
            {
                foreach ($options as $key => $value)
                {
                    $this->{$key} = $value;
                    $this->_count++;
                }
            }
        }

        public function count(){
            return $this->_count;
        }
        
    }
}