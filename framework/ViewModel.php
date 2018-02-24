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

                if(!empty($section)){
                    if(isset($map[$section])){
                        return $map[$section];
                    }
                }

                return $map;

            }else{
                return array();
            }

        }

        public function getItems($name=null)
        {

            $map = array();

            $maps = !empty($this->_map) ? $this->_map:array();

            /*print("<pre>maps<br>");
            print_r($maps);
            print("</pre>");*/

            if(!empty($maps)){
                
                if(is_string($name) && isset($maps[$name])){
                    $map = $maps[$name];
                }

                if(empty($map) || !isset($map["items"])){
                    $map["items"] = $maps;
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