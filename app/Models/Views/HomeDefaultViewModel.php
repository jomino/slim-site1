<?php

namespace App\Models\Views;

use App\Statics\Models as STATICS;

class HomeDefaultViewModel extends \Framework\ViewModel
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
    * @read
    */
    protected $_map = array(
        array(
            "tpl" => "row",
            "layout" => STATICS::BS_LAYOUT_3COL,
            "items" => array(
                array(
                    "id" => "contacts",
                    "items" => array(
                        array(
                            "tpl" => "info-box",
                            "style" => "light-blue-gradient",
                            "text" => "messages.home_contact_title",
                            "name" => "info_box_contacts"
                        ),
                        array(
                            "tpl" => "box",
                            "style" => "primary",
                            "classes" => array("flat"),
                            "title" => "messages.contact_title",
                            "name" => "describ_box_contacts"
                        )
                    )
                ),
                array(
                    "id" => "properties",
                    "items" => array(
                        array(
                            "tpl" => "info-box",
                            "style" => "light-blue-gradient",
                            "text" => "messages.home_properties_title",
                            "name" => "info_box_properties"
                        ),
                        array(
                            "tpl" => "box",
                            "style" => "primary",
                            "classes" => array("flat"),
                            "title" => "messages.property_title",
                            "name" => "describ_box_properties"
                        )
                    )
                ),
                array(
                    "id" => "contracts",
                    "items" => array(
                        array(
                            "tpl" => "info-box",
                            "style" => "light-blue-gradient",
                            "text" => "messages.home_contracts_title",
                            "name" => "info_box_contracts"
                        ),
                        array(
                            "tpl" => "box",
                            "style" => "primary",
                            "classes" => array("flat"),
                            "title" => "messages.contract_title",
                            "name" => "describ_box_contracts"
                        )
                    )
                )
            )
        )
    );

    /**
    * @override
    */
    protected function _setItems($items,$parent=null)
    {
        $datas = $this->datas ?:array();

        $get_uid = function(){ return dechex(mt_rand(0xf000,0xffff)); };
        
        for($j=0;$j<sizeof($items);$j++){

            $item = $items[$j];

            if(isset($item["items"])){

                if(!isset($item["id"])){
                    if($parent && isset($parent["id"])){
                        $item["id"] = $parent["id"]."El".$get_uid();
                    }else{
                        $item["id"] = uniqid()."El";
                    }
                }

                $item["items"] = $this->_setItems($items[$j]["items"],$item);

            }else{

                if(!isset($item["id"])){
                    $id = $get_uid()."El";
                    if($parent && isset($parent["id"])){
                        $item["id"] = $parent["id"]."-".$id;
                    }else{
                        $item["id"] = $id;
                    }
                }

                if(isset($item["name"]) && isset($datas[$item["name"]])){
                    $_datas = call_user_func_array($datas[$item["name"]],[$this->_container]);
                    $item = array_merge($item,$_datas);
                    unset($item["name"]);
                }

            }

            $items[$j] = $item;

        }

        return $items;

    }
    
}
    