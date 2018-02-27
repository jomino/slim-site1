<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Registry as Registry;
    use Framework\Template\Exception as Exception;
    
    class ViewModel extends Base
    {
        /**
        * @read
        */
        protected $_map;

        public function getMap($section=null)
        {
            if(!empty($this->_map)){

                $map = $this->_map;

                if(is_string($section) && isset($map[$section])){
                    return $map[$section];
                }

                return $map;

            }else{
                return array();
            }

        }

        public function getItems($name=null)
        {

            $map = array();

            $t_map = $this->map;

            /*print("<pre>maps<br>");
            print_r($maps);
            print("</pre>");*/

            if(!empty($t_map)){
                
                if(is_string($name) && isset($t_map[$name])){
                    $map = $t_map[$name];
                }else if(!isset($t_map["items"])){
                    $map["items"] = $t_map;
                }else{
                    $map = $t_map;
                }

                /*print("<pre>map:before<br>");
                print_r($map);
                print("</pre>");*/

                $items = $this->_setItems($map["items"],$map);

                /*print("<pre>map:after<br>");
                print_r($map);
                print("</pre>");*/

                $map["items"] = $items;

            }

            return $map;

        }

        /**
        * @override
        */
        protected function _setItems($items,$map){
            return $items;
        }

        public function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public static function getRawMap()
        {
            $self = new static();
            return $self->map;
        }

    }    
}