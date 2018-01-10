<?php

namespace Framework
{
    class ArrayMethods
    {
        private function __construct()
        {
            // do nothing
        }
        
        private function __clone()
        {
            // do nothing
        }
        
        public static function clean($array)
        {
            return array_filter($array, function($item) {
                return !empty($item);
            });
        }
        
        public static function trim($array)
        {
            return array_map(function($item) {
                return trim($item);
            }, $array);
        }
        
        public static function first($array)
        {
            if (sizeof($array) == 0)
            {
                return null;
            }
            
            $keys = array_keys($array);
            return $array[$keys[0]];
        }
        
        public static function last($array)
        {
            if (sizeof($array) == 0)
            {
                return null;
            }
            
            $keys = array_keys($array);
            return $array[$keys[sizeof($keys) - 1]];
        }
        
        public static function toObject($array)
        {
            $result = new \stdClass();
            
            foreach ($array as $key => $value)
            {
                if (is_array($value))
                {
                    $result->{$key} = self::toObject($value);
                }
                else
                {
                    $result->{$key} = $value;
                }
            }
            
            return $result;
        }
        
        public static function flatten($array, $return = array())
        {
            foreach ($array as $key => $value)
            {
                if (is_array($value) || is_object($value))
                {
                    $return = self::flatten($value, $return);
                }
                else
                {
                    $return[] = $value;
                }
            }
            
            return $return;
        }

        public static function column_recursive($haystack,$needle) {
            $found = [];
            array_walk_recursive($haystack, function($value, $key) use (&$found, $needle) {
                if ($key === $needle){
                    $found[] = $value;
                }
            });
            return $found;
        }

        /*private static function _column_recursive($input = null, $key = null)
        {
            $value = null;
            foreach ($input as $k=>$v) {
                if (is_array($v)) {
                    $value = static::_column_recursive($v,$key);
                }else{
                    if($key==$k){
                        $value = $v;
                    }
                }
            }
            return $value;
        }

        public static function column_recursive($input = null, $key = null)
        {

            $argc = func_num_args();
            $params = func_get_args();

            if ($argc < 2) {
                return null;
            }

            if (!is_array($params[0])) {
                return null;
            }

            if (!is_int($params[1])
                && !is_float($params[1])
                && !is_string($params[1])
                && $params[1] !== null
                && !(is_object($params[1]) && method_exists($params[1], '__toString'))
            ) {
                return false;
            }

            $resultArray = array();

            foreach ($input as $k=>$v) {
                $value = null;
                if (is_array($v)) {
                    $t_value = self::_column_recursive($v,$key);
                    if($t_value){
                        $value = $t_value;
                    }
                }else{
                    if($key==$k){
                        $value = $v;
                    }
                }
                if($value){
                    $resultArray[] = $value;
                }
            }

            return $resultArray;

        }*/
        
        public static function column($input = null, $columnKey = null, $indexKey = null)
        {
            $argc = func_num_args();
            $params = func_get_args();
            if ($argc < 2) {
                return null;
            }
            if (!is_array($params[0])) {
                return null;
            }
            if (!is_int($params[1])
                && !is_float($params[1])
                && !is_string($params[1])
                && $params[1] !== null
                && !(is_object($params[1]) && method_exists($params[1], '__toString'))
            ) {
                return false;
            }
            if (isset($params[2])
                && !is_int($params[2])
                && !is_float($params[2])
                && !is_string($params[2])
                && !(is_object($params[2]) && method_exists($params[2], '__toString'))
            ) {
                return false;
            }
            $paramsInput = $params[0];
            $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
            $paramsIndexKey = null;
            if (isset($params[2])) {
                if (is_float($params[2]) || is_int($params[2])) {
                    $paramsIndexKey = (int) $params[2];
                } else {
                    $paramsIndexKey = (string) $params[2];
                }
            }
            $resultArray = array();
            foreach ($paramsInput as $row) {
                $key = $value = null;
                $keySet = $valueSet = false;
                if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                    $keySet = true;
                    $key = (string) $row[$paramsIndexKey];
                }
                if ($paramsColumnKey === null) {
                    $valueSet = true;
                    $value = $row;
                } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                    $valueSet = true;
                    $value = $row[$paramsColumnKey];
                }
                if ($valueSet) {
                    if ($keySet) {
                        $resultArray[$key] = $value;
                    } else {
                        $resultArray[] = $value;
                    }
                }
            }
            return $resultArray;
        }

        public function toQueryString($array)
        {
            return http_build_query(
                self::clean(
                    $array
                )
            );
        }
    }    
}