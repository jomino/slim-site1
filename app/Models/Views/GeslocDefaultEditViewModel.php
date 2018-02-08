<?php

namespace App\Models\Views;

use App\Statics\Models as STATICS;

use Framework\DateMethods as DateMethods;

class GeslocDefaultEditViewModel extends \Framework\ViewModel
{
    /**
    * @readwrite
    */
    protected $_logger;

    /**
    * @readwrite
    */
    protected $_model;

    /**
    * @readwrite
    */
    protected $_validators = array();
    
    /**
    * @read
    */
    protected $_map = array(
        "form-edit" => array(
            "id" => "property-form-edit",
            "title" => "messages.title_user_edit",
            "tpl" => "form-horizontal vtop",
            "items" => array(
                array(
                    "tpl" => "row",
                    "layout" => STATICS::BS_LAYOUT_4COL,
                    "items" => array(
                        array(
                            // [+]id (html id)
                            // [+]label (displayed label) returned by model
                            // [+]value (displayed value) returned by model
                            // [+]raw (original value) returned by model
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "ref", // mandatory
                            "default" => ":empty_string",
                            "locked" => 1,
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "pattern" => "[A-Za-z0-9\u00C0-\u00D6\u00D8-\u00f6\u00f8-\u00ff\s-]*",
                                    "maxlength" => 128
                                ),
                                "messages" => array(
                                    "pattern" => "default.error_invalid_chars"
                                )
                            )
                        ),
                        array(
                            // [+]id (html id)
                            // [+]label (displayed label) returned by model
                            // [+]value (displayed value) returned by model
                            // [+]raw (original value) returned by model
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "ref_bail", // mandatory
                            "default" => ":empty_string",
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "pattern" => "[A-Za-z0-9/-]*",
                                    "maxlength" => 128
                                ),
                                "messages" => array(
                                    "pattern" => "default.error_invalid_chars"
                                )
                            )
                        ),
                        array(
                            // [+]id (html id)
                            // [+]label (displayed label) returned by model
                            // [+]value (displayed value) returned by model
                            // [+]raw (original value) returned by model
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "ref_etat", // mandatory
                            "default" => ":empty_string",
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "pattern" => "[A-Za-z0-9/-]*",
                                    "maxlength" => 128
                                ),
                                "messages" => array(
                                    "pattern" => "default.error_invalid_chars"
                                )
                            )
                        )
                    )
                ),
                array(
                    "tpl" => "row",
                    "layout" => STATICS::BS_LAYOUT_4COL,
                    "items" => array(
                        array(
                            "tpl" => "form-select",
                            "required" => 1,
                            "locked" => 1,
                            "field" => "idpro",
                            "label" => "default.owner", // label must be defined here (can't extract from model)
                            "error" => "default.error_required_field",
                            "placeholder" => "messages.plhd_select",
                            "displayField" => array("pnom","nom"),
                            "valueField" => "id_ref",
                            "filter" => array(
                                array(
                                    "name" => "id_utype",
                                    "value" => STATICS::USER_TYPE_OWNER
                                )
                            ),
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "select2" => true,
                                    "required" => true
                                )
                            )
                        ),
                        array(
                            "tpl" => "form-select",
                            "required" => 1,
                            "locked" => 1,
                            "field" => "idloc",
                            "label" => "default.tenant", // label must be defined here
                            "error" => "default.error_required_field",
                            "placeholder" => "messages.plhd_select",
                            "displayField" => array("pnom","nom"),
                            "valueField" => "id_ref",
                            "filter" => array( array(
                                "name" => "id_utype",
                                "value" => STATICS::USER_TYPE_TENANT
                            )),
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "select2" => true,
                                    "required" => true
                                )
                            )
                        ),
                        array(
                            "tpl" => "form-select",
                            "reset" => 1,
                            "locked" => 1,
                            "field" => "idges",
                            "label" => "default.syndic", // label must be defined here
                            "error" => "default.error_required_field",
                            "placeholder" => "messages.plhd_select",
                            "displayField" => array("pnom","nom"),
                            "valueField" => "id_ref",
                            "filter" => array( array(
                                "name" => "id_utype",
                                "value" => STATICS::USER_TYPE_SYNDIC
                            )),
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "select2" => true
                                )
                            )
                        )
                    )
                ),
                array(
                    "tpl" => "row",
                    "items" => array(
                        array(
                            // [+]id (html id)
                            // [+]label (displayed label) returned by model
                            // [+]value (displayed value) returned by model
                            // [+]raw (original value) returned by model
                            // [+]name (html name) returned by proc
                            "tpl" => "form-currency",
                            "locked" => 1,
                            "layout" => STATICS::BS_LAYOUT_6COL,
                            "field" => "prix", // mandatory
                            "default" => ":zero_float",
                            "icon_after" => '<i class="fa fa-euro"></i>',
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "pattern" => "[0-9- ]*,?[0-9]*"
                                ),
                                "messages" => array(
                                    "pattern" => "default.error_invalid_chars"
                                )
                            )
                        ),
                        array(
                            "tpl" => "form-checkbox",
                            "locked" => 1,
                            "layout" => STATICS::BS_LAYOUT_CELL,
                            "label" => "", 
                            "classes" => array( "min-height-80" ),
                            "tooltip" => "messages.gesloc_tlp_endebit", 
                            "field" => "endebit"
                        )
                    )
                ),
                array(
                    "tpl" => "row",
                    "layout" => STATICS::BS_LAYOUT_2COL,
                    "items" => array(
                        array(
                            // [+]id (returned by proc) // mandatory for form-button 
                            "type" => "button", // mandatory for form-button[button|submit|reset]
                            "tpl" => "form-button",
                            "label" => "default.form_reset",
                            "classes" => array(
                                "btn-primary",
                                "btn-flat",
                                "btn-reset",
                                "pull-left"
                            )
                        ),
                        array(
                            // [+]id (returned by proc) // mandatory for form-button 
                            "type" => "button", // mandatory for form-button[button|submit|reset]
                            "tpl" => "form-button",
                            "label" => "default.form_save",
                            "classes" => array(
                                "btn-primary",
                                "btn-flat",
                                "btn-submit",
                                "pull-right"
                            )
                        )
                    )
                )
            )
        )
    );

    /**
    * @override
    */
    public function getItems($name=null)
    {
        $map = parent::getItems($name);

        if(!empty($this->_validators)){
            $map["validate"] = $this->_validators;
        }

        return $map;

    }

    /**
    * @override
    */
    protected function _setItems($items,$parent=null)
    {
        $class = $this->model;
        
        $model = is_string($class) ? new $class():$this->model;

        $is_new = $model->phantom==true;

        $withLocal = $model->withLocal==true;
        
        for($j=0;$j<sizeof($items);$j++){

            $item = $items[$j];

            if(isset($item["items"])){

                $item["items"] = $this->_setItems($items[$j]["items"],$item);

            }else{

                if(!isset($item["id"])){
                    if($parent && isset($parent["id"])){
                        $item["id"] = $parent["id"]."El".dechex(mt_rand(0xf000,0xffff));
                    }else{
                        $item["id"] = uniqid()."El";
                    }
                }

                if(isset($item["field"])){

                    $column = $model->getColumn($item["field"]);
                    $c_name = $column["name"];

                    if(empty($item["name"])){
                        $item["name"] = $c_name;
                    }

                    //$item["debug"] = print_r($model,true);

                    if($withLocal){
                        if(!isset($item["label"])){ $item["label"] = $model->getLabels()->{$c_name}; }
                        if(!$is_new){ $item["value"] = !empty($model->getDisplay()->{$c_name}) ? $model->getDisplay()->{$c_name}:""; }
                    }else{
                        if(!isset($item["label"])){ $item["label"] = $c_name; }
                        if(!$is_new){ $item["value"] = $model->getRaw()->{$c_name}; }
                    }

                    if(!isset($item["raw"])){
                        if(!$is_new){ $item["raw"] = !empty($model->getRaw()->{$c_name}) ? $model->getRaw()->{$c_name}:""; }
                        else{ $item["raw"] = "-1"; }
                    }

                    if(!empty($column["belongto"])){
                        $where = array();
                        $valueField = null;
                        $belongto = explode(".",$column["belongto"]);
                        $className = "\\App\\Models\\".ucfirst($belongto[0]);
                        if(isset($item["valueField"])){ $valueField = $item["valueField"]; }
                        if(isset($item["displayField"])){ $displayField = $item["displayField"]; }
                        else{ $displayField = strtolower($belongto[sizeof($belongto)-1]); }
                        if(isset($item["filter"])){
                            for($i=0;$i<sizeof($item["filter"]);$i++){
                                $filter = $item["filter"][$i];
                                $where[$filter["name"]." = ?"] = $filter["value"];
                            }
                        }
                        $item["list"] = $this->_getList($className,$displayField,$where,$valueField);
                        if(is_string($displayField)){ 
                            $c_path = $c_name.".".$displayField;
                            $label = $model->getBelongTo($c_path."#label");
                            if(is_string($label)){ $item["label"] = $label; }
                            else{ /*$this->_logger->debug("[UsersDefaultEditViewModel]LABEL.ERROR",$label);*/ }
                            if(!$is_new){ $item["value"] = $model->getBelongTo($c_path."#display"); }
                        }else{
                            if(!$is_new){
                                $_value = "";
                                for($i=0;$i<sizeof($item["displayField"]);$i++){
                                    $c_path = $c_name.".".$item["displayField"][$i];
                                    $_belongto = $model->getBelongTo($c_path."#display");
                                    if(is_string($_belongto)){ $_value .= " ".$_belongto; }
                                    else{ /*$this->_logger->debug("[UsersDefaultEditViewModel]VALUE.ERROR ({$c_path}) : ",$_belongto);*/ }
                                }
                                $item["value"] = trim($_value);
                            }
                        }
                    }

                    if(!isset($item["value"]) || empty($item["value"])){
                        if(isset($item["default"])){
                            switch($item["default"]){
                                case ":date_now":
                                    $item["value"] = DateMethods::now("fr");
                                    $item["raw"] = DateMethods::now();
                                break;
                                case ":zero_float":
                                    $item["value"] = "0,00";
                                    $item["raw"] = "0.00";
                                break;
                                case ":empty_string":
                                    $item["raw"] = $item["value"] = "";
                                break;
                                default:
                                    $item["value"] = $item["default"];
                            }
                        }else{
                            $item["value"] = "";
                        }
                    }

                    if(!isset($item["placeholder"])){
                        if($is_new){
                            $item["placeholder"] = "messages.plhd_".$item["tpl"];
                        }else{
                            if(empty($item["value"]) || empty($item["raw"])){
                                $item["placeholder"] = "messages.plhd_".$item["tpl"];
                            }
                        }
                    }

                    if(isset($item["validate"])){

                        if(empty($this->_validators["rules"])){
                            $this->_validators["rules"] = array();
                        }

                        $this->_validators["rules"] = array_merge(
                            $this->_validators["rules"],
                            array($c_name=>$item["validate"]["rules"])
                        );

                        if(isset($item["validate"]["messages"])){

                            if(empty($this->_validators["messages"])){
                                $this->_validators["messages"] = array();
                            }

                            $this->_validators["messages"] = array_merge(
                                $this->_validators["messages"],
                                array($c_name=>$item["validate"]["messages"])
                            );

                        }

                    }

                    $item["readonly"] = !$is_new && isset($item["locked"]);

                }

            }

            $items[$j] = $item;

        }

        return $items;

    }

    protected function _getList($model,$properties,$where=array(),$from="id")
    { // !important: sortir l'opération du flux
        $t_resp = array();
        $_res = $model::all($where);
        $_props = is_array($properties) ? $properties:array($properties);
        if(!empty($_res)){
            for($j=0;$j<sizeof($_res);$j++){
                $t_name = "";
                $t_val = $_res[$j]->{$from};
                for($i=0;$i<sizeof($_props);$i++){
                    $t_name .= " ".($_res[$j]->withLocal ? 
                        $_res[$j]->getDisplay()->{$_props[$i]}:
                        $_res[$j]->{$_props[$i]});
                }
                $t_resp[] = array(
                    "name" => trim($t_name),
                    "value" => $t_val
                );
            }
        }
        return($t_resp);
    }

}