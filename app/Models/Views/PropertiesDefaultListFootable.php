<?php

namespace App\Models\Views;

use Framework\ArrayMethods as ArrayMethods;

class PropertiesDefaultListFootable extends \Framework\ViewModel
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
        "properties" => array(
            array(
                "type" => "field",
                "field" => "id_prop",
                "column" => array(
                    "type" => "'number'",
                    "name" => "'id_prop'", // mandatory
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "price",
                "column" => array(
                    "type" => "'number'",
                    "name" => "'price'",
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
                "type" => "list",
                "list" => "\App\Models\Ptypes",
                "field" => "ref_ptype", // mandatory
                "column" => array(
                    "type" => "'array'",
                    "name" => "'ptypes'", // mandatory
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
                    "style" => array("min-width" => "10%")
                )
            ),
            array(
                "index" => 1,
                "type" => "field",
                "field" => "id_ptype",
                "action" => array("property-ptype"),
                "column" => array(
                    "type" => "'text'",
                    "name" => "'id_ptype'",
                    "title" => "default.type",
                    "style" => array("min-width" => "10%")
                )
            ),
            array(
                "index" => 2,
                "type" => "field",
                "field" => "name",
                "column" => array(
                    "title" => "default.name",
                    "name" => "'name'",
                    "style" => array("min-width" => "10%")
                )
            ),
            array(
                "index" => 3,
                "type" => "fn",
                "delegate" => "street",
                "column" => array(
                    "title" => "default.adress",
                    "name" => "'full_adress'",
                    "formatter" => "$.jo.footableFormatter('property-full-adress')",
                    "style" => array("width" => "100%"),
                    "sortable" => "false"
                )
            ),
            array(
                "index" => 4,
                "name" => "action_properties_edit",
                "type" => "fn",
                //[+] "action" => array(),
                "column" => array(
                    "type" => "'text'",
                    "name" => "'properties_edit'",
                    "title" => " ",
                    "style" => array("width" => "32px"),
                    "sortable" => "false"
                    //[+] "formatter" => string::javascript
                )
            )
        )
    );

    public function getColumns()
    {
        $map = $this->getMap("properties");
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