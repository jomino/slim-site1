<?php

namespace Framework\Service\Connector
{

    use Framework\Events as Events;
    use Framework\Service as Service;
    use Framework\Request as Request;
    use Framework\Service\Exception as Exception;
    
    class Json extends Service\Connector
    {
        
        /**
        * @readwrite
        */
        protected $_handcheck = self::CREDENTIAL_TYPE_NONE;
        
        /**
        * @readwrite
        * @comment shoud be a class with log/pass properties
        */
        protected $_credential;
        
        /**
        * @readwrite
        */
        protected $_tokens = array();
        
        
        /**
        * @readwrite
        */
        protected $_service;
        
        /**
        * @readwrite
        */
        protected $_url;
        
        /**
        * @readwrite
        */
        protected $_username;
        
        /**
        * @readwrite
        */
        protected $_password;
        
        /**
        * @readwrite
        */
        protected $_params;
        
        /**
        * @readwrite
        */
        protected $_lastError = "no_error";
        
        /**
        * @readwrite
        */
        protected $_headers = array();
            
        /**
        * @read
        */
        protected $_defaultHeaders = array(
            "Content-type" => "application/json; charset=utf-8",
            "Pragma" => "no-cache"
        );
        
        public function __construct($options = array())
        {
            parent::__construct($options);

            if($this->handcheck!=self::CREDENTIAL_TYPE_NONE){
                // todo get token(s) with credential
            }

        }
        
        public function query()
        {
            return new Service\Query\Json(array(
                "connector" => $this
            ));
        }
        
        public function execute($payload)
        {

            $url = $this->url;
            $datas = array();

            if($this->handcheck!=self::CREDENTIAL_TYPE_NONE){
                // todo set token(s) on credential method
            }

            $exec = $this->_exec($url,$payload);

            $response = trim($exec->getBody());

            // var_dump($response);

            return $response;

        }

        public function getLastError()
        {
            return $this->_lastError;
        }
        
        protected function getService()
        {
            $options = array(
                "headers" => array_merge($this->headers,$this->_defaultHeaders)
            );
            if($this->referer){ $options["referer"] = $this->referer; }
            return new Request($options);
        }
        
        protected function _exec($u,$p)
        {
            $service = $this->service;
            //var_dump($service);
            return $service->post($u,$p);
        }
        
    }

}