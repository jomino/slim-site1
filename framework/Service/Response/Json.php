<?php

namespace Framework\Service\Response
{

    use Framework\Service as Service;

    class Json extends Service\Response
    {

        /**
        * @readwrite
        */
        protected $_payload;

        public function getResponse()
        {
            $response = $this->parse();
            return $response;
        }

        // to be overriden
        protected function parse()
        {
            return false;
        }
        
    }
}