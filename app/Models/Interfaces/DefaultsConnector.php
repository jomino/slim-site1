<?php

namespace App\Models\Interfaces
{

    use Framework\Service\Connector as Connector;
    use Framework\RequestMethods as RequestMethods;
    
    class DefaultsConnector extends Connector\Json
    {
        
        /**
        * @read
        */
        protected $_withCredential = false;

        /**
        * @readwrite
        */
        protected $_interface;

        /**
        * @read
        */
        protected $_url = "http://www.immozoom.be/extjs/extjs.router.php";

        /**
        * @read
        * @getter
        */
        protected function getHeaders()
        {
            return array(
                "X-Requested-With" => "XMLHttpRequest",  
                "Origin" => "http://".RequestMethods::server("HTTP_HOST")
            );
        }

        /**
        * @read
        * @getter
        */
        protected function getReferer()
        {
            return "http://".RequestMethods::server("HTTP_HOST").RequestMethods::server("PHP_SELF");
        }

    }

}
     