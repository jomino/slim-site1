<?php

namespace Framework
{
    use Framework\Core\Exception\Json as JSONException;

    class RequestMethods
    {
        private function __construct()
        {
            // do nothing
        }
        
        private function __clone()
        {
            // do nothing
        }
        
        public static function get($key, $default = "")
        {
            if (!empty($_GET[$key]))
            {
                return $_GET[$key];
            }
            return $default;
        }
        
        public static function post($key, $default = "")
        {
            if (!empty($_POST[$key]))
            {
                return $_POST[$key];
            }
            return $default;
        }
        
        public static function server($key, $default = "")
        {
            if (!empty($_SERVER[$key]))
            {
                return $_SERVER[$key];
            }
            return $default;
        }
        
        public static function cookie($key, $default = "")
        {
            if (!empty($_COOKIE[$key]))
            {
                return $_COOKIE[$key];
            }
            return $default;
        }
        
        public static function rawdata()
        {
            return file_get_contents("php://input");
        }
        
        public static function getJson()
        {

            $rawdata = self::rawdata();

            if(0===strpos($rawdata,"{") || 0===strpos($rawdata,"[")){

                $json_decoded = json_decode($rawdata);

                if(json_last_error()>0){
                    throw new JSONException(json_last_error());
                }

                return $json_decoded;

            }

            if (!empty($_POST)){
                return (object) $_POST;
            }

            return;
            
        }
    }
}