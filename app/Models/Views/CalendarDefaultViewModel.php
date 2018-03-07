<?php

namespace App\Models\Views;

use App\Statics\Models as STATICS;

class CalendarDefaultViewModel extends \Framework\ViewModel
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
            "items" => array(
                array(
                    "layout" => STATICS::BS_LAYOUT_1ON4,
                    "items" => array(
                        array(
                            "tpl" => "cmp-tline",
                            "id" => "calendar-aside",
                            "target" => "full_cal_tline"
                        )
                    )
                ),
                array(
                    "layout" => STATICS::BS_LAYOUT_3ON4,
                    "items" => array(
                        array(
                            "tpl" => "cmp-box",
                            "id" => "calendar-wrapper",
                            "items" => array(
                                array(
                                    "target" => "full_cal_html"
                                )
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
            }


            $items[$j] = $item;

        }

        return $items;

    }
    
}
    