<?php

namespace Framework\Localisation
{

    use Framework\ArrayMethods as ArrayMethods;
    
    class Format
    {

        private $type = 0;
        private $field;
        private $formats;
        public $generic;
        private $raw;
        
        public static $fn_ext = '_FORMAT';

        //setter
        
        public function setFormats($formats){
            $this->formats = (object) $formats;
        }
        
        public function setGenericFormats($formats){
            $this->generic = (object) $formats;
            $default = new \stdClass();
            foreach($this->generic as $props){
                foreach($props as $k=>$v){
                    $default->{$k} = $v;
                }
            }
            if(!$this->generic){ $this->generic = new  \stdClass(); }
            if(!isset($this->formats)){ $this->formats = new  \stdClass(); }
            $this->generic->default = clone $default;
            $this->formats->default = clone $default;
        }
        
        public function setField($field){
            $this->field = $field;
        }
        
        public function setType($type){
            if($type!=$this->type){ $this->type = $type; }
        }
        
        //getter
        
        public function getFormats($path=''){
            $formats = $this->formats;
            //print("format::getFormats => {$path}<br/>");
            /*print_r($formats);
            print("<br/>");*/
            if($path===''){ return($formats); }
            else{
                $format = $this->getPropertyFrom($formats,$path);
                if($format==$formats){
                    $format = $this->getGenericFormats($path);
                }
                return($format);
            }
            return;
        }
        
        public function getGenericFormats($path=''){
            $generic = $this->generic;
            //print("format::getGenericFormats => {$path}<br/>");
            if($path===''){ return($generic); }
            else{ return($this->getPropertyFrom($generic,$path)); }
            
        }
        
        public function getField(){
            return($this->field);
        }
        
        public function getType(){
            return($this->type);
        }
        
        // constructor
        
        public function __construct($field,$raw,$formats,$generic,$type=0){
            $this->raw = (object) $raw;
            $this->type = $type;
            $this->field = $field;
            $this->formats = (object) $formats;
            $this->setGenericFormats($generic);
        }
        
        private function _proceed($path,$value,$format=null){
            if(empty($format)){ $format = $this->getFormats($path); }
            if(isset($format->type))
            {
                $type = $format->type;
                switch($type){
                    case 'fn':
                        $fn = strtoupper($format->{$type}).static::$fn_ext;
                    break;
                    default:
                        $fn = strtoupper($type).static::$fn_ext;
                }
                // print("format::_proceed => {$fn}({$value},{$path})<br/>");
                $return = method_exists($this,$fn) ? call_user_func_array(array($this,$fn),array($value,$path)):$this->BYPASS_FORMAT($value);
                return ($return);
            }
            return $path;
        }
        
        public function values(){
            $values = array();
            $field = $this->field;
            $value = $this->raw->{$field};
            $format = $this->getFormats("format");
            if(isset($format->type)){
                if(!in_array($format->type,array("list","values"))){    
                    $return = ArrayMethods::trim(explode("_",$this->value($value,false)));
                    $format = $this->getGenericFormats(implode(".", $return));
                    if(!isset($format->type)){
                        return $values;
                    }
                }
                $type = $format->type;
                $values = $format->{$type};  
                if($type=="values"){
                    $t_values = array();
                    foreach($values as $k=>$v){
                        $t_obj = new \stdClass();
                        $t_obj->id = $k;
                        $t_obj->name = $v;
                        $t_values[] = $t_obj;
                    }
                    $values = $t_values;
                }
            }
            return $values;
        }

        public function value($value,$assert=true){
            //print("format::value => {$value}<br/>");
            $return = $this->_proceed("format",$value);
            /*print("return: ".trim($return)."<br/>");
            print("---------------------------------------------------------<br/>");
            print("<br/>");*/
            return ($assert==true) ? $this->_assert($return):$return;
        }
        
        public function label(){
            //print("format::label => {$value}<br/>");
            $return = $this->_proceed("label",null);
            /*print("return: ".trim($return)."<br/>");
            print("---------------------------------------------------------<br/>");
            print("<br/>");*/
            return $this->_assert($return);
        }
        
        public function getGeneric($path,$value=null){
            $return = $this->_proceed($path,$value);
            return $this->_assert($return);
        }
        
        public function assert($value=""){
            return $this->_assert($value);
        }
        
        private function _assert($value){
            $default = "default";
            $t_type = 0;
            $t_field = $this->field;
            $t_value = isset($this->raw->{$t_field}) ? $this->raw->{$t_field}:null;
            // print("_assert::before : {$value}------------------------------><br/>");
            while(preg_match("#\\{[^}]*\\}#",$value) || strpos($value,"_{$default}_")!==false ){
                $ret_arr = array();
                if(preg_match("#\\{([^}]*)\\}#",$value,$ret_arr)){
                    $count = sizeof($ret_arr);
                    if($count>1){
                        for($i=1;$i<$count;$i++){
                            $name = $o_name = $ret_arr[$i];
                            if(false!==strpos($name,",")){
                                $t_names = explode(",",$name);
                                $type = intval($t_names[1]);
                                $name = $t_names[0];
                                $this->type = $type;
                            }else{
                                $this->type = $t_type;
                            }
                            $f_path = "{$default}.{$name}";
                            $return = $this->_proceed($f_path,$t_value);
                            $o_value = $value;
                            $value = str_replace('{'.$o_name.'}',$return,$value);
                            
                            if($o_value===$value){ break; }
                            
                        }
                    }
                }
                $this->type = $t_type;
                if(false!==strpos($value,"_{$default}_")){
                    $ret_arr = explode(' ',str_replace("_{$default}_",'',$value));
                    
                    for($i=0;$i<sizeof($ret_arr);$i++){
                        $name = $ret_arr[$i];
                        $f_path = "{$default}.{$name}";
                        $return = $this->_proceed($f_path,$t_value);
                        $value = str_replace("_{$default}_{$name}",$return,$value);
                    }
                }
            }
            // print("_assert::after ".trim($value)."<br/>");
            return $value;
        }
        
        private function BYPASS_FORMAT($value){ return($value); }

        private function NUMERIC_FORMAT($value){ return(intval($value)); }

        private function FLOAT_FORMAT($value){ return(floatval($value)); }

        private function TEXT_FORMAT($value){
            if(is_string($value) && $value!=''){
                return( html_entity_decode( 
                    stripslashes("{$value}"),ENT_COMPAT | ENT_NOQUOTES, 'UTF-8'));
            }else{ 
                return("");
            }
        }

        private function VALUES_FORMAT($value,$format=''){
            $t_values = (object) $this->getFormats($format.'.values');
            return property_exists($t_values,$value)? stripslashes($t_values->{$value}):'{nc}';
        }

        private function DATE_FORMAT($value,$format=''){
            $t_format = (object) $this->getFormats($format);
            $f_patt = $t_format->pattern;
            $f_date = $value>0 ? date($f_patt,strtotime($value)):'{nc}';
            return $f_date;
        }

        private function CHOICE_FORMAT($value,$format=''){
            $t_path = $format.'.choice';
            $t_choice = $this->getFormats($t_path);
            return $this->getValuesFrom($t_choice);
        }

        private function LIST_FORMAT($value,$format=''){
            $t_path = $format.'.list';
            $t_list = $this->getFormats($t_path);
            foreach($t_list as $i ){
                if(isset($i->id) && $i->id==$value){
                    return isset($i->name) ? $i->name:null;
                }
            }
            return '{nc}';
        }

        private function MATCH_FORMAT($value,$format=''){
            $sp = ' ';
            $return = '';
            $t_format = (object) $this->getFormats($format);
            $match = $t_format->match;
            $t_regxps = $match->regxps;
            $t_values = $match->values;
            $t_proc = $match->proc;
            if($t_proc=='Reference'){
                for($j=0;$j<count($t_values);$j++){
                    $t_ref = $t_values[$j]->reference;
                    $o_path = implode( '.', ArrayMethods::trim(explode('_',$t_ref)));
                    $r_values = $this->getFormats($o_path);
                    $t_values[$j] = $r_values->values;
                }
            }
            if(isset($t_format->delegate)){
                $t_deleg = $t_format->delegate;
                if(!empty($this->raw) && isset($this->raw->{$t_deleg})){
                    $value = $this->raw->{$t_deleg};
                    //print("debug(".$t_deleg.") : {$value}<br>");
                }else{
                    // print("error(".$this->field.") : {$t_deleg}<br>");
                    return $value;
                }
            }
            for($i=0;$i<count($t_regxps);$i++){
                $regxps = '#'.stripslashes($t_regxps[$i]).'#';
                if(preg_match( $regxps, stripslashes($value), $m1)){
                    $t_value = $t_values[$i];
                    if(is_string($t_value)){
                        $replacement = preg_match( '#[$][1]#', $t_value)? $m1[0]:$t_value;
                    }else{
                        $t_value = (object) $t_value;
                        switch($t_proc){
                            case 'Typed':
                                if(isset($t_value->type)){
                                    $t_type = $t_value->type;
                                    $fn = strtoupper($t_value->{$t_type}).static::$fn_ext;
                                    $replacement = method_exists($this,$fn)? call_user_func_array(array($this,$fn),array($value,$format)):$this->BYPASS_FORMAT($value);
                                }
                                if(isset($t_value->reference)){
                                    $t_ref = $t_value->reference;
                                    //print("t_ref : ".trim($t_ref)."<br/>");
                                    if(preg_match("#^[^_{]#",$t_ref)){ $replacement = "_{$t_ref}"; }
                                    else{ $replacement = $t_ref; }
                                    //print("replacement : ".trim($replacement)."<br/>");
                                }
                            break;
                            default:
                                if(isset($t_value->{$m1[1]})){
                                    $replacement = $t_value->{$m1[1]};
                                }else{
                                    $replacement = "error";
                                }
                        }
                    }
                    $return .= $replacement.$sp;
                }
            }
            return(trim($return,' '));
        }
        
        private function OUINON_FORMAT($value,$format=''){
            $value = ($value!='' && $value!='X' && $value!='N') ? '1':'0';
            $format = (object) $this->getGenericFormats('formats.ouinon');
            $type = $format->type;
            $values = $format->{$type};
            return($values->{$value});
        }

        private function XON_FORMAT($value,$format=''){
            $value = ($value!='')? $value:'X';
            $format = (object) $this->getGenericFormats('formats.xon');
            $type = $format->type;
            $values = $format->{$type};
            return($values->{$value});
        }

        private function DEC2M2_FORMAT($value,$format=''){
            $value = number_format(floatval($value), 2 , ',', ' ');
            if(is_numeric($value) && $value>0){
                $gm2 = $this->getGenericFormats('generic.sqm');
                $type = $gm2->type;
                $return = $value.' '.$this->getValuesFrom($gm2->{$type});
            }else{
                $return = "0,00";
            }
            return($return);
        }

        private function METER_FORMAT($value,$format=''){
            if($value>0){
                $gm = $this->getGenericFormats('generic.meter');
                $type = $gm->type;
                $value = number_format(floatval($value), 2 , ',', ' ');
                $return = $value.' '.$this->getValuesFrom($gm->{$type});
            }else{
                $return = "{nc}";
            }
            return($return);
        }
        
        private function CURRENCY_FORMAT($value,$format=''){
            if($value>0){
                $gc = $this->getGenericFormats('generic.currency');
                $type = $gc->type;
                $value = number_format(floatval($value), 2 , ',', ' ');
                $return = $value.' '.$this->getValuesFrom($gc->{$type});
            }else{
                $return = "{nc}";
            }
            return($return);
        }

        //utils
        
        public function getPropertyFrom($obj,$path=''){
            //print("format::getPropertyFrom => {$path}<br/>");
            if($path===''){ return $obj; }
            $arr_path = explode('.',$path);
            $obj_ret = $obj;
            for($i=0;$i<sizeof($arr_path);$i++){
                $prop = $arr_path[$i];
                if($prop!=""){ 
                    if(isset($obj_ret->{$prop})){
                        $obj_ret = $obj_ret->{$prop};
                    }else{
                        return($obj);
                    }
                }
            }
            /*print_r($obj_ret);
            print("<br/>");*/
            return($obj_ret);
        }

        public function getValuesFrom($choice=array(),$name='text'){
            /*print("format::getValuesFrom => {$name}[".$this->type."]<br/>");
            print("choice =><br/>");
            print_r($choice);
            print("<br/>");*/
            if(count($choice)==0 || $name==''){ return 'no_choice'; }
            $value = isset($choice[$this->type]->{$name})? $choice[$this->type]->{$name}:"no_text[{$this->type}]";
            //print("found => {$value}<br/>");
            return($value);
        }
        
    }

}