<?php

namespace App\Models\Views;

use App\Statics\Models as STATICS;

use Framework\DateMethods as DateMethods;

class StartupDefaultViewModel extends \Framework\ViewModel
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
        "properties" => array(
            "id" => "property-form-edit",
            "title" => "messages.title_user_edit",
            "tpl" => "form-horizontal vtop",
            "items" => array(
                array(
                    "tpl" => "row",
                    "layout" => STATICS::BS_LAYOUT_4COL,
                    "items" => array(
                        array(
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "id_ref", // mandatory
                            "default" => ":empty_string",
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
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "name", // mandatory
                            "default" => ":empty_string",
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
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-currency",
                            "field" => "price", // mandatory
                            "default" => ":zero_float",
                            "icon_after" => '<i class="fa fa-euro"></i>',
                            "classes" => array( "min-height-80" ),
                            "required" => 1,
                            "validate" => array(
                                "rules" => array(
                                    "pattern" => "[0-9- ]*,?[0-9]*",
                                    "form_currency" => true
                                ),
                                "messages" => array(
                                    "pattern" => "default.error_invalid_chars"
                                )
                            )
                        ),
                        array(
                            "tpl" => "form-select",
                            "reset" => 1,
                            "placeholder" => "messages.plhd_select",
                            "field" => "id_ptype",
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
                            "layout" => STATICS::BS_LAYOUT_CELL,
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "num", // mandatory
                            "default" => ":empty_string",
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
                            "layout" => STATICS::BS_LAYOUT_4COL,
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "street", // mandatory
                            "default" => ":empty_string",
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
                            "layout" => STATICS::BS_LAYOUT_4COL,
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "ville", // mandatory
                            "default" => ":empty_string",
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
                            "layout" => STATICS::BS_LAYOUT_CELL,
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "cp", // mandatory
                            "default" => ":empty_string",
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "pattern" => "[A-Za-z0-9-]*",
                                    "maxlength" => 128
                                ),
                                "messages" => array(
                                    "pattern" => "default.error_invalid_chars"
                                )
                            )
                        ),
                        array(
                            "layout" => STATICS::BS_LAYOUT_4COL,
                            "tpl" => "form-select",
                            "required" => 1,
                            "reset" => 1,
                            "error" => "default.error_required_field",
                            "placeholder" => "messages.plhd_select",
                            "field" => "id_cty",
                            "delegate" => "name",
                            "classes" => array( "min-height-80" ),
                            "validate" => array(
                                "rules" => array(
                                    "select2" => true,
                                    "required" => true
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
                            "layout" => STATICS::BS_LAYOUT_1COL,
                            "type" => "text", // mandatory for form-input
                            "tpl" => "form-input",
                            "field" => "comment", // mandatory
                            "default" => ":empty_string",
                            "classes" => array( "min-height-80" )
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
                        $belongto = explode(".",$column["belongto"]);
                        $className = "\\App\\Models\\".ucfirst($belongto[0]);
                        if(isset($item["delegate"])){ $classProperty = $item["delegate"]; }
                        else{ $classProperty = strtolower($belongto[sizeof($belongto)-1]); }
                        $c_path = $c_name.".".$classProperty;
                        $item["list"] = $this->_getList($className,$classProperty);
                        $label = $model->getBelongTo($c_path."#label");
                        if(!empty($label) && is_string($label)){ $item["label"] = $label; }
                        else{ $this->_logger->debug("[UsersDefaultEditViewModel]LABEL_ERROR",$label); }
                        if(!$is_new){ $item["value"] = $model->getBelongTo($c_path."#display"); }
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

                }

            }

            $items[$j] = $item;

        }

        return $items;

    }

    protected function _getList($className,$classProperty,$where=array(),$from="id")
    { // !important: sortir l'opération du flux
        $t_resp = array();
        $_res = $className::all($where);
        if(!empty($_res)){
            for($j=0;$j<sizeof($_res);$j++){
                if(is_array($classProperty)){
                    $t_name = "";
                    for($i=0;$i<sizeof($classProperty);$i++){
                        $t_name .= " ".($_res[$j]->withLocal ? 
                            $_res[$j]->getDisplay()->{$classProperty[$i]}:
                            $_res[$j]->{$classProperty[$i]});
                    }
                }else{
                    $t_name = $_res[$j]->withLocal ? 
                        $_res[$j]->getDisplay()->{$classProperty}:
                        $_res[$j]->{$classProperty};
                }
                $t_resp[] = array(
                    "name" => trim($t_name),
                    "value" => $_res[$j]->{$from}
                );
            }
        }
        return($t_resp);
    }

}