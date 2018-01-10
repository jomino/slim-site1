<?php

namespace App\Models\Views;

use Framework\ArrayMethods as ArrayMethods;

class GeslocpayListFootable extends \Framework\ViewModel
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
        "geslocpay" => array(
            array(
                "type" => "field",
                "field" => "idpay",
                "column" => array(
                    "type" => "'number'",
                    "name" => "'idpay'", // mandatory
                    "visible" => "false"
                )
            ),
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
                "field" => "agence",
                "column" => array(
                    "type" => "'text'",
                    "name" => "'agence'", // mandatory
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "rem",
                "column" => array(
                    "type" => "'text'",
                    "name" => "'rem'", // mandatory
                    "visible" => "false"
                )
            ),
            array(
                "type" => "list",
                "list" => "\App\Models\Gptypes",
                "field" => "ref_gptype", // mandatory
                "column" => array(
                    "type" => "'array'",
                    "name" => "'gptypes'", // mandatory
                    "visible" => "false"
                )
            ),
            array(
                "index" => 0,
                "type" => "field",
                //"field" => "paytype.ref_gptype#display",
                "field" => "paytype",
                "action" => array("gesloc-pay-gptype"),
                "column" => array(
                    "type" => "'text'",
                    "name" => "'paytype'",
                    "title" => " ",
                    "style" => array("width" => "15%")
                )
            ),
            array(
                "index" => 1,
                "type" => "field",
                "field" => "refpay",
                "column" => array(
                    "type" => "'text'",
                    "name" => "'refpay'",
                    "title" => "default.reference_cb",
                    "style" => array("min-width" => "15%"),
                    "sortable" => "false"
                )
            ),
            array(
                "index" => 2,
                "type" => "field",
                "field" => "dt_debit",
                "action" => array("short-date-fr"),
                "column" => array(
                    "type" => "'text'",
                    "title" => "default.due_date",
                    "name" => "'dt_debit'",
                    "style" => array("width" => "15%")
                )
            ),
            array(
                "index" => 3,
                "type" => "field",
                "field" => "debitsum",
                "action" => array("number-gt-zero"),
                "column" => array(
                    "type" => "'number'",
                    "name" => "'debitsum'",
                    "title" => " € ",
                    "style" => array("width" => "15%"),
                    "sortable" => "false"
                )
            ),
            array(
                "index" => 4,
                "type" => "field",
                "field" => "dt_credit",
                "action" => array("short-date-fr"),
                "column" => array(
                    "type" => "'text'",
                    "title" => "default.pay_date",
                    "name" => "'dt_credit'",
                    "style" => array("width" => "15%")
                )
            ),
            array(
                "index" => 5,
                "type" => "field",
                "field" => "creditsum",
                "action" => array("number-gt-zero"),
                "column" => array(
                    "type" => "'number'",
                    "name" => "'creditsum'",
                    "title" => " € ",
                    "style" => array("width" => "100%"),
                    "sortable" => "false"
                )
            ),
            array(
                "index" => 6,
                "name" => "action_edit_pay",
                "type" => "fn",
                //[+] "action" => array(),
                "column" => array(
                    "type" => "'text'",
                    "name" => "'edit_pay'",
                    "title" => " ",
                    "style" => array("width" => "32px"),
                    "sortable" => "false"
                    //[+] "formatter" => string::javascript
                )
            ),
            array(
                "index" => 7,
                "name" => "action_delete_pay",
                "type" => "fn",
                //[+] "action" => array(),
                "column" => array(
                    "type" => "'text'",
                    "name" => "'del_pay'",
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
        $assets = $this->assets;
        $map = $this->getMap("geslocpay");
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