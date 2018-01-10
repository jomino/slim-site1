<?php

namespace App\Models\Views;

use Framework\ArrayMethods as ArrayMethods;

class GeslocListFootable extends \Framework\ViewModel
{

    /**
    *@readwrite
    */
    protected $_data;
    
    /**
    *@readwrite
    */
    protected $_assets;

    /**
    *@read
    */
    protected $_footableFormatter = "$.jo.footableFormatter('%s','%s')";

    /**
    *@read
    */
    protected $_map = array(
        "gesloc" => array(
            array(
                "type" => "field",
                "field" => "idgesloc",
                "column" => array(
                    "type" => "'number'",
                    "name" => "'idgesloc'", // mandatory
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "endebit",
                "column" => array(
                    "type" => "'number'",
                    "name" => "'endebit'", // mandatory
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
                "field" => "ref",
                "column" => array(
                    "type" => "'text'",
                    "name" => "'ref'",
                    "title" => "default.reference",
                    "style" => array("min-width" => "10%")
                )
            ),
            array(
                "index" => 1,
                "type" => "fn",
                "delegate" => "street",
                "action" => array('gesloc-full-adress'),
                "column" => array(
                    "title" => "default.adress",
                    "name" => "'full_adress'",
                    "style" => array("width" => "100%"),
                    "sortable" => "false"
                    //[+] "formatter" => string::javascript
                )
            ),
            array(
                "index" => 2,
                "name" => "action_pay",
                "type" => "fn",
                //[+] "action" => array(),
                "column" => array(
                    "type" => "'text'",
                    "name" => "'action_pay'",
                    "title" => " ",
                    "style" => array("width" => "32px"),
                    "sortable" => "false"
                    //[+] "formatter" => string::javascript
                )
            )/*,
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
            )*/
        )
    );

    public function getColumns()
    {
        $assets = $this->assets;
        $map = $this->getMap("gesloc");
        for($i=0;$i<sizeof($map);$i++){
            $item = $map[$i];
            if(isset($item["name"]) && !empty($this->_data)){
                if(isset($this->_data[$item["name"]])){
                    $item = array_merge($item,$this->_data[$item["name"]]);
                }
            }
            if($item["type"]=="fn" && isset($item["action"])){
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