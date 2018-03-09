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
            "field" => "id_msgtype",
            "column" => array(
                "type" => "'number'",
                "name" => "'id_msgtype'",
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
            "field" => "street",
            "column" => array(
                "type" => "'text'",
                "name" => "'street'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "num",
            "column" => array(
                "type" => "'number'",
                "name" => "'num'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "cp",
            "column" => array(
                "type" => "'text'",
                "name" => "'cp'",
                "visible" => "false"
            )
        ),
        array(
            "type" => "field",
            "field" => "ville",
            "column" => array(
                "type" => "'text'",
                "name" => "'ville'",
                "visible" => "false"
            )
        ),
        array(
            "index" => 0,
            "type" => "field",
            "field" => "id_ref",
            "column" => array(
                "type" => "'text'",
                "name" => "'id_ref'",
                "title" => "default.reference",
                "style" => array("width" => "10%")
            )
        ),
        array(
            "index" => 1,
            "type" => "fn",
            "delegate" => "nom",
            "column" => array(
                "title" => "default.name",
                "name" => "'full_name'",
                "formatter" => "$.jo.footableFormatter('contact-full-name')",
                "style" => array("width" => "15%")
            )
        ),
        array(
            "index" => 2,
            "type" => "fn",
            "delegate" => "street",
            "column" => array(
                "title" => "default.adress",
                "name" => "'full_adress'",
                "formatter" => "$.jo.footableFormatter('contact-full-adress')",
                "style" => array("width" => "45%")
            )
        ),
        array(
            "index" => 3,
            "type" => "field",
            "field" => "phone",
            "column" => array(
                "type" => "'text'",
                "name" => "'phone'",
                "title" => "default.phone",
                "style" => array("width" => "15%"),
                "sortable" => "false"
            )
        ),
        array(
            "index" => 4,
            "type" => "field",
            "field" => "email",
            "column" => array(
                "type" => "'text'",
                "name" => "'email'",
                "title" => "default.email",
                "style" => array("width" => "15%")
            )
        ),
        array(
            "index" => 5,
            "name" => "action_contact_edit",
            "type" => "fn",
            //[+] "action" => array(),
            "column" => array(
                "type" => "'text'",
                "name" => "'contact_edit'",
                "title" => " ",
                "style" => array("width" => "32px"),
                "sortable" => "false"
                //[+] "formatter" => string::javascript
            )
        )
    );

    public function getColumns()
    {
        $map = $this->getMap("contacts");
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