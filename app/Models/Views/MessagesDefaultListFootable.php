<?php

namespace App\Models\Views;

use Framework\ArrayMethods as ArrayMethods;

class MessagesDefaultListFootable extends \Framework\ViewModel
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
            "field" => "proceed",
            "column" => array(
                "type" => "'number'",
                "name" => "'proceed'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "method",
            "method" => array(
                "fn" => "attach",
                "params" => "uid"
            ),
            "column" => array(
                "type" => "'number'",
                "name" => "'attach'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "nom",
            "column" => array(
                "type" => "'text'",
                "name" => "'nom'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "pnom",
            "column" => array(
                "type" => "'text'",
                "name" => "'pnom'",
                "visible" => "false"
            )
        ),
        array(
            "index" => 0,
            "action" => array("action-message-checked"),
            "column" => array(
                "type" => "'text'",
                "name" => "'message_checked'",
                "style" => array("width" => "32px"),
                "sortable" => "false",
                "filterable" => "false"
            )
        ),
        array(
            "index" => 1,
            "name" => "action_message_read",
            "column" => array(
                "type" => "'text'",
                "name" => "'message_read'",
                "style" => array("width" => "32px"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 2,
            "name" => "action_message_name",
            "options" => array( "classes" => "mailbox-name" ),
            "column" => array(
                "type" => "'text'",
                "name" => "'contact'",
                "style" => array("min-width" => "25%","white-space" => "nowrap"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 3,
            "type" => "field",
            "field" => "title",
            "name" => "action_message_title",
            "options" => array( "classes" => "mailbox-subject" ),
            "column" => array(
                "type" => "'text'",
                "name" => "'title'",
                "style" => array("width" => "75%"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 4,
            "action" => array("action-message-attach"),
            "column" => array(
                "type" => "'text'",
                "name" => "'message_attach'",
                "style" => array("width" => "32px"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 5,
            "action" => array("action-message-date"),
            "type" => "field",
            "field" => "received",
            "options" => array(
                "classes" => "mailbox-date"
            ),
            "column" => array(
                "type" => "'text'",
                "name" => "'received'",
                "style" => array("min-width" => "10%","white-space" => "nowrap"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 6,
            "type" => "method",
            "method" => array( "fn" => "sentOrReceived" ),
            "action" => array("action-message-sent"),
            "column" => array(
                "type" => "'number'",
                "name" => "'sent_or_received'",
                "style" => array("width" => "32px"),
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