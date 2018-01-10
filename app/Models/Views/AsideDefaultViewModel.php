<?php

namespace App\Models\Views;

class AsideDefaultViewModel extends \Framework\ViewModel
{
    /**
    * @readwrite
    */
    protected $_datas;

    /**
    * @readwrite
    */
    protected $_localisation;
        
    /**
    * @read
    */
    protected $_map = array(
        "sidebarmenu" => array(
            "id" => "default-menu",
            "label" => "Menu",
            "items" => array(
                array(
                    "name" => "contacts_all",
                    "label" => "messages.contacts_all",
                    "iconCls" => "fa fa-circle-o",
                    "type" => "get", // for attr data-link
                    "href" => "", // for attr data-href
                    "params" => "", // for attr data-params
                    "for" => "" // for attr data-for
                ),
                array(
                    "name" => "properties_all",
                    "label" => "messages.properties_all",
                    "iconCls" => "fa fa-circle-o",
                    "type" => "get", // for attr data-link
                    "href" => "", // for attr data-href
                    "params" => "", // for attr data-params
                    "for" => "" // for attr data-for
                ),
                array(
                    "name" => "gesloc_all",
                    "label" => "messages.gesloc_all",
                    "iconCls" => "fa fa-circle-o",
                    "type" => "get", // for attr data-link
                    "href" => "", // for attr data-href
                    "params" => "", // for attr data-params
                    "for" => "" // for attr data-for
                )/*,
                array(
                    "label" => "messages.finance_title",
                    "menu" => array(
                        array(
                            "label" => "messages.debt",
                            "name" => "finance_all",
                            "iconCls" => "",
                            //[+] widget array(:cls,:label)
                            "type" => "", // for attr data-link
                            "href" => "", // for attr data-href
                            "params" => "", // for attr data-params
                            "for" => "" // for attr data-for
                        ),
                        array(
                            "label" => "Link 2",
                            "iconCls" => "",
                            //[+] widget array(:cls,:label)
                            "type" => "", // for attr data-link
                            "href" => "", // for attr data-href
                            "params" => "", // for attr data-params
                            "for" => "" // for attr data-for
                        ),
                        array(
                            "label" => "Link 3",
                            "iconCls" => "",
                            //[+] widget array(:cls,:label)
                            "type" => "", // for attr data-link
                            "href" => "", // for attr data-href
                            "params" => "", // for attr data-params
                            "for" => "" // for attr data-for
                        )
                    )
                )*/
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

            if(isset($item["label"])){
                $item["label"] = $this->localisation->trans($item["label"]);
            }

            if(isset($item["menu"])){

                if(!isset($item["id"])){
                    if($parent && isset($parent["id"])){
                        $item["id"] = $parent["id"]."El".$get_uid();
                    }else{
                        $item["id"] = uniqid()."El";
                    }
                }

                $item["menu"] = $this->_setItems($items[$j]["menu"],$item);

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
                    $item = array_merge($item,$datas[$item["name"]]);
                }

            }

            $items[$j] = $item;

        }

        return $items;

    }
    
}
    