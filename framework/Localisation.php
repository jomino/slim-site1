<?php

namespace Framework
{
    use Framework\Base as Base;
    use Framework\Localisation\Format as Format;
    use Framework\Localisation\Exception as Exception;
    
    class Localisation extends Base
    {
        
        /**
        * @read
        */
        protected $_defaultExtention = "json";
        
        /**
        * @readwrite
        */
        protected $_defaultPath = "application/localisation";
        
        /**
        * @read
        */
        protected $_defaultGeneric = "default";
        
        /**
        * @readwrite
        */
        protected $_defaultLanguage = "fr";
        
        /**
        * @readwrite
        */
        protected $_formats = array();
        
        /**
        * @readwrite
        */
        protected $_genericFormat;
        
        /**
        * @readwrite
        */
        protected $_language;
        
        /**
        * @read
        */
        public static $JSON_ERRORS = array(
            JSON_ERROR_NONE => 'No error',
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
            JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
            JSON_ERROR_SYNTAX => 'Syntax error',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
        );
        
        public function initialize($language="")
        {
            if(empty($language)){ $language = $this->_defaultLanguage; }
            $this->_language = $language;
            $this->_loadGeneric();
            return $this;
        }
	
	    public function getText($path,$type=0){
            $generic = $this->genericFormat;
            $format = new Format(null,null,null,$generic,$type);
            $return = $format->getGeneric($path);
            return($return);
        }
	
	    public function getValue($path,$value,$type=0){
            $generic = $this->genericFormat;
            $format = new Format(null,null,null,$generic,$type);
            $return = $format->getGeneric($path,$value);
            return($return);
        }
	
	    public function getValues($path="",$type=0){
            if(empty($path)){ return; }
            $values = null;
            $generic = $this->genericFormat;
            $t_format = new Format(null,null,null,$generic,$type);
            $format = $t_format->getPropertyFrom($t_format->generic,$path);
            if(isset($format->type)){
                if(!in_array($format->type,array("list","values"))){    
                    return;
                }
                $t_values = array();
                $f_type = $format->type;
                $values = $format->{$f_type};  
                if($type=="values"){
                    foreach($values as $k=>$v){
                        $t_obj = new \stdClass();
                        $t_obj->name = $k;
                        $t_obj->value = $t_format->assert($v);
                        $t_values[] = $t_obj;
                    }
                    $values = $t_values;
                }  
                if($f_type=="list"){
                    for($i=0;$i<sizeof($values);$i++){
                        $t_obj = new \stdClass();
                        $t_obj->name = $values[$i]->id;
                        $t_obj->value = $t_format->assert($values[$i]->name);
                        $t_values[] = $t_obj;
                    }
                    $values = $t_values;
                }
            }
            return($values);
        }
	
	
	    public function format($field,$name,$raw){
            $type = 0;
            $generic = $this->genericFormat;
            $formats = $this->_getFormat($name);
            if($formats && isset($formats->{$field})){
                $format = $formats->{$field};
                if($format != new \stdClass()){ // ! obj vide
                    $instance = new Format($field,$raw,$format,$generic,$type);
                    return($instance);
                }
            }
            return;
        }

        protected function _getFormat($name="")
        {
            $formats = $this->formats;
            if(isset($formats[$name])){ return($formats[$name]); }
            if($format=$this->_loadFormat($name)){
                $formats = array_merge( $formats, array(
                    "{$name}" => $format
                ));
                $this->_formats = $formats;
                return($format);
            }else{
                return null;
            }
            
        }
        
        protected function _loadGeneric()
        {
            $defaultGeneric = $this->defaultGeneric;
            $genericFormat = $this->_loadFormat($defaultGeneric);
            $this->_genericFormat = $genericFormat;
        }

        protected function _loadFormat($name)
        {
            $file_dir = array( $this->defaultPath, $this->language);
            $file_ext = $this->defaultExtention;
            $file_name = "{$name}.{$file_ext}";
            $file_path = implode("/",$file_dir)."/".$file_name;
            //print("file_path: {$file_path}<br/>");
            if(file_exists($file_path)){
                $json = file_get_contents($file_path);
                $format = (object) json_decode($json, false, 512, JSON_UNESCAPED_SLASHES);
                $last_error = json_last_error();
                if($last_error != JSON_ERROR_NONE){
                    $error = isset(self::$JSON_ERRORS[$last_error]) ? self::$JSON_ERRORS[$last_error] : 'Unknown error';
                    return $error;
                }
                return ($format);
            }
            return;
        }
        
        protected function _getExceptionForImplementation($method)
        {
            return new Exception\Implementation("{$method} method not implemented");
        }
        
    }

}
