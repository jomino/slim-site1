<?php

namespace Framework\Service\Request
{

    use Framework\Service as Service;

    class Json extends Service\Request
    {
        
        /**
        * @readwrite
        */
        protected $_query;
        
        /**
        * @readwrite
        */
        protected $_request;

        public function __construct($options=array())
        {
            parent::__construct($options);
            $this->_request = new \stdClass();
        }

        public function getRequest()
        {
            $request = $this->_request;
            return json_encode($request);
        }

        protected function setProperty($name,$value)
        {
            $this->_request->{$name} = $value;
        }
        
    }
}