<?php

namespace Framework\Database
{
    use Framework\Base as Base;
    use Framework\Registry as Registry;
    use Framework\ArrayMethods as ArrayMethods;
    use Framework\Database\Exception as Exception;
    use Framework\Database\Response as Response;
    
    class Response extends Base
    {

        /**
        * @read
        */
        protected $_defaultType = "";
        
        /**
        * @readwrite
        */
        protected $_type = "";
        
        /**
        * @readwrite
        */
        protected $_recordset;

        /**
        * @readwrite
        */
        protected $_types = array(
            "json",
            "datatable"
        );
        
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }

        public function getResponse($type=""){
            if($type==""){ $type = $this->type; }
            $rawvalues = array_map(array($this,"getValues"),$this->recordset);
            switch($type){
                case "json":
                    return json_encode( array(
                       "result" => $rawvalues
                    ));
                break;
                case "datatable":
                    $response = new Response\Datatable($rawvalues);
                    return $response;
                break;
                default:
                    return $rawvalues;
            }
        }

        public function getValues($record){
            try{
                return $record->display;
            }catch( Exception $e){
                return array();
            }
        }
                    
    }

}    
