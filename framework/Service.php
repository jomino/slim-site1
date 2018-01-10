<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Service\Connector as Connector;
    use Framework\Service\Exception as Exception;
    
    class Service extends Base
    {
        /**
        * @readwrite
        */
        protected $_type;
        
        /**
        * @readwrite
        */
        protected $_options;
        
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function __construct($options = array())
        {
            
            parent::__construct($options);
            
            if (!$this->type)
            {
                throw new Exception\Argument("Invalid type");
            }
            
            switch ($this->type)
            {
                case "json":
                {
                    return new Connector\Json($this->options);
                    break;
                }
                default:
                {
                    throw new Exception\Argument("Invalid type");
                    break;
                }
            }

        }
        
    }

}