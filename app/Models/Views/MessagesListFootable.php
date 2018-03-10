<?php

namespace App\Models\Views;

use Framework\ArrayMethods as ArrayMethods;

class MessagesListFootable extends \Framework\ViewModel
{
    /**
    *@readwrite
    */
    protected $_data;

    /**
    *@read
    */
    protected $_footableFormatter = "$.jo.footableFormatter('%s','%s')";

    /**
    * @read
    */
    protected $_map = array(
        array(
            "type" => "field",
            "field" => "id_msg",
            "column" => array(
                "type" => "'number'",
                "name" => "'id_msg'", // mandatory    
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "id_cls",
            "column" => array(
                "type" => "'number'",
                "name" => "'id_cls'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "id_msgtype",
            "column" => array(
                "type" => "'number'",
                "name" => "'id_msgtype'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "read",
            "column" => array(
                "type" => "'number'",
                "name" => "'read'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "attach",
            "column" => array(
                "type" => "'number'",
                "name" => "'attach'",
                "visible" => "false"
            )
        ),
        array(
            "index" => 0,
            "name" => "action_message_checked",
            "type" => "fn",
            "column" => array(
                "type" => "'text'",
                "name" => "'message_checked'",
                "style" => array("width" => "32px")
            )
        ),
        array(
            "index" => 1,
            "name" => "action_message_read",
            "type" => "fn",
            "column" => array(
                "type" => "'text'",
                "name" => "'message_read'",
                "style" => array("width" => "32px")
            )
        ),
        array(
            "index" => 2,
            "type" => "field",
            "field" => "contact",
            "column" => array(
                "type" => "'text'",
                "name" => "'contact'",
                "style" => array("min-width" => "10%","white-space" => "nowrap")
            )
        ),
        array(
            "index" => 3,
            "type" => "field",
            "field" => "title",
            "column" => array(
                "type" => "'text'",
                "name" => "'title'",
                "style" => array("width" => "100%"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 4,
            "name" => "action_message_attach",
            "type" => "fn",
            "column" => array(
                "type" => "'text'",
                "name" => "'message_attach'",
                "style" => array("width" => "32px")
            )
        ),
        array(
            "index" => 5,
            "name" => "action_message_date",
            "type" => "fn",
            "column" => array(
                "type" => "'text'",
                "name" => "'message_date'",
                "style" => array("min-width" => "10%","white-space" => "nowrap"),
                "sortable" => "false"
            )
        )
    );

    public function getColumns()
    {
        $map = $this->getMap();
        for($i=0;$i<sizeof($map);$i++){
            $item = $map[$i];
            if(isset($item["name"]) && !empty($this->_data)){
                if(isset($this->_data[$item["name"]])){
                    $item = array_merge($item,$this->_data[$item["name"]]);
                }
            }
            if(isset($item["action"])){
                $item["column"]["formatter"] = sprintf(
                    $this->footableFormatter,
                    $item["action"][0],
                    isset($item["action"][1]) ? $item["action"][1]:""
                );
            }
            $map[$i] = $item;
        }
        return ArrayMethods::column($map,"column");
    }
    
}