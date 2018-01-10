<?php

namespace Framework\Service
{
    use Framework\Base as Base;
    use Framework\Service\Exception as Exception;
    
    class Connector extends Base
    {      

        const CREDENTIAL_TYPE_NONE = 0;
        const CREDENTIAL_TYPE_COOKIE = 1;
        const CREDENTIAL_TYPE_HTTP = 2;
        
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
    }
}