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
                    "tpl" => "cmp-info",
                    "style" => "light-blue-gradient",
                    "target" => "info_box_contacts"
                ),
                array(
                    "tpl" => "cmp-info",
                    "style" => "light-blue-gradient",
                    "target" => "info_box_properties"
                ),
                array(
                    "tpl" => "cmp-info",
                    "style" => "light-blue-gradient",
                    "target" => "info_box_contracts"
                )
            )
        ),
        array(
            "tpl" => "row",
            "layout" => STATICS::BS_LAYOUT_3COL,
            "items" => array(
                array(
                    "tpl" => "cmp-box",
                    "style" => "primary",
                    "title" => "messages.contact_box_title",
                    "target" => "pils_box_contacts",
                    "css" => array("min-height: 450px;"),
                    "items" => array(
                        array(
                            "tpl" => "cmp-line",
                            "classes" => array( "h5", "bold"),
                            "text" => "messages.contact_list_title"
                        ),
                        array(
                            "tpl" => "cmp-dlist",
                            "type" => "list-unstyled",
                            "target" => "list_box_contacts"
                        )
                    )
                ),
                array(
                    "tpl" => "cmp-box",
                    "style" => "primary",
                    "title" => "messages.properties_box_title",
                    "target" => "pils_box_properties",
                    "css" => array("min-height: 450px;"),
                    "items" => array(
                        array(
                            "tpl" => "cmp-chart",
                            "target" => "chart_box_properties"
                        ),
                        array(
                            "target" => "chart_detail_properties"
                        )
                    )
                ),
                array(
                    "tpl" => "cmp-box",
                    "style" => "primary",
                    "title" => "messages.contracts_title",
                    "target" => "pils_box_contracts",
                    "css" => array("min-height: 450px;"),
                    "items" => array(
                        array(
                            "tpl" => "cmp-dlist",
                            "type" => "dl-list",
                            "horizontal" => 1,
                            "target" => "list_box_contracts"
                        ),
                        array(
                            // items added by BodyDefaultHomeController class 
                            "tpl" => "row",
                            "layout" => STATICS::BS_LAYOUT_3COL,
                            "target" => "knobs_box_contracts"
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

            if(isset($item["target"]) && isset($datas[$item["target"]])){
                $_datas = call_user_func_array($datas[$item["target"]],[$this->_container]);
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
            }


            $items[$j] = $item;

        }

        return $items;

    }
    
}
    