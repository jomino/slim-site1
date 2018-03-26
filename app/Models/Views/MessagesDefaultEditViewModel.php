<?php

namespace App\Models\Views;

use App\Statics\Models as STATICS;

class MessagesDefaultEditViewModel extends \Framework\ViewModel
{
    /**
    * @readwrite
    */
    protected $_datas;
    
    /**
    * @readwrite
    */
    protected $_container;

    /**
    * @readwrite
    */
    protected $_validators = array();
        
    /**
    * @read
    */
    protected $_map = array(
        array(
            "id" => "mailbox-wrapper",
            "tpl" => "form-horizontal vtop",
            "layout" => STATICS::BS_LAYOUT_1COL,
            "items" => array(
                array(
                    "tpl" => "form-select",
                    "label" => "messages.mailbox_list_from",
                    "placeholder" => "messages.plhd_select",
                    "target" => "mailbox_list_from",
                    "classes" => array( "min-height-80" ),
                    "required" => 1,
                    "validate" => array(
                        "rules" => array(
                            "select2" => true,
                            "required" => true
                        )
                    )
                ),
                array(
                    "tpl" => "form-input",
                    "type" => "text",
                    "required" => 1,
                    "label" => "messages.mailbox_edit_title",
                    "placeholder" => "messages.plhd_input",
                    "classes" => array( "min-height-80" ),
                    "target" => "mailbox_edit_title",
                    "validate" => array(
                        "rules" => array(
                            "required" => true
                        ),
                        "messages" => array(
                            "pattern" => "default.error_required_field"
                        )
                    )
                ),
                array(
                    "tpl" => "form-textarea",
                    "target" => "mailbox_edit_content"
                ),
                array(
                    "tpl" => "row",
                    "items" => array(
                        array(
                            "tpl" => "form-checkbox",
                            "layout" => STATICS::BS_LAYOUT_4COL,
                            "label" => "messages.mailbox_wcopy_to",
                            "name" => "wcopyto",
                            "raw" => 0
                        ),
                        array(
                            "tpl" => "form-checkbox",
                            "layout" => STATICS::BS_LAYOUT_4COL,
                            "label" => "messages.mailbox_wcopy_from",
                            "name" => "wcopyfrom",
                            "raw" => 0
                        )
                    )
                ),
                array(
                    "tpl" => "form-hr"
                ),
                array(
                    "tpl" => "row",
                    "layout" => STATICS::BS_LAYOUT_2COL,
                    "items" => array(
                        array(
                            // [+]id (returned by proc) // mandatory for form-button 
                            "type" => "button", // mandatory for form-button[button|submit|reset]
                            "tpl" => "form-button",
                            "label" => "messages.mailbox_attach",
                            "classes" => array(
                                "btn-primary",
                                "btn-flat",
                                "btn-attach",
                                "pull-left"
                            )
                        ),
                        array(
                            // [+]id (returned by proc) // mandatory for form-button 
                            "type" => "button", // mandatory for form-button[button|submit|reset]
                            "tpl" => "form-button",
                            "label" => "default.mailbox_save",
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
        $datas = $this->datas ?:array();

        $get_uid = function(){ return dechex(mt_rand(0xf000,0xffff)); };
        
        for($j=0;$j<sizeof($items);$j++){

            $item = $items[$j];

            if(isset($item["target"]) && isset($datas[$item["target"]])){
                if(is_callable($datas[$item["target"]])){ $_datas = call_user_func_array($datas[$item["target"]],[$this->_container]); }
                else{ $_datas = $datas[$item["target"]]; }
                $item = array_merge_recursive($item,$_datas);
                unset($item["target"]);
            }

            if(!isset($item["id"])){
                $id = $get_uid()."El";
                if($parent && isset($parent["id"])){
                    $item["id"] = $parent["id"]."-".$id;
                }else{
                    $item["id"] = $id;
                }
            }

            if(isset($items[$j]["items"])){
                $item["items"] = $this->_setItems($items[$j]["items"],$item);
            }else if(isset($item["items"])){
                $item["items"] = $this->_setItems($item["items"],$item);
            }else{

                if(isset($item["validate"]) && isset($item["name"])){

                    if(empty($this->_validators["rules"])){
                        $this->_validators["rules"] = array();
                    }

                    $this->_validators["rules"] = array_merge(
                        $this->_validators["rules"],
                        array($item["name"]=>$item["validate"]["rules"])
                    );

                    if(isset($item["validate"]["messages"])){

                        if(empty($this->_validators["messages"])){
                            $this->_validators["messages"] = array();
                        }

                        $this->_validators["messages"] = array_merge(
                            $this->_validators["messages"],
                            array($item["name"]=>$item["validate"]["messages"])
                        );

                    }

                }

            }


            $items[$j] = $item;

        }

        return $items;

    }
    
}
    