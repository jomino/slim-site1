<?php

namespace App\Models\Views;

use Framework\Registry as Registry;

class AsideDefaultViewModel extends \Framework\ViewModel
{
    /**
    * @readwrite
    */
    protected $_datas;
        
    /**
    * @read
    */
    protected $_map = array(
        array(
            "id" => "heading-title",
            "target" => "heading_title",
            "heading" => array( "label" => "Menu" )
        ),
       array(
            "id" => "contacts-all-link",
            "target" => "contacts_all",
            "label" => "messages.contacts_all",
            "iconCls" => "fa fa-circle-o",
            "type" => "get"
        ),
        array(
            "id" => "properties-all-link",
            "target" => "properties_all",
            "label" => "messages.properties_all",
            "iconCls" => "fa fa-circle-o",
            "type" => "get"
        ),
        array(
            "id" => "contracts-all-link",
            "target" => "contracts_all",
            "label" => "messages.gesloc_all",
            "iconCls" => "fa fa-circle-o",
            "type" => "get"
        ),
        array(
            "id" => "actions-title",
            "heading" => array( "label" => "Gestion" )
        ),
        array(
            "id" => "actions-menu-users",
            "label" => "messages.actions_menu_users",
            "iconCls" => "fa fa-user",
            "items" => array(
                array(
                    "target" => "menu_users_add",
                    "label" => "messages.menu_users_add",
                    "iconCls" => "fa fa-plus-square-o",
                    "type" => "get"
                ),
                array(
                    "target" => "menu_users_send",
                    "label" => "messages.menu_users_send",
                    "iconCls" => "fa fa-envelope-o",
                    "type" => "get"
                )
            )
        ),
        array(
            "id" => "actions-menu-properties",
            "label" => "messages.actions_menu_properties",
            "iconCls" => "fa fa-home",
            "items" => array(
                array(
                    "target" => "menu_properties_add",
                    "label" => "messages.menu_properties_add",
                    "iconCls" => "fa fa-plus-square-o",
                    "type" => "get"
                ),
                array(
                    "target" => "menu_properties_send",
                    "label" => "messages.menu_properties_send",
                    "iconCls" => "fa fa-envelope-o",
                    "type" => "get"
                )
            )
        ),
        array(
            "id" => "actions-menu-contracts",
            "label" => "messages.actions_menu_contracts",
            "iconCls" => "fa fa-file-text",
            "items" => array(
                array(
                    "target" => "menu_contracts_add",
                    "label" => "messages.menu_contracts_add",
                    "iconCls" => "fa fa-plus-square-o",
                    "type" => "get"
                ),
                array(
                    "target" => "menu_contracts_send",
                    "label" => "messages.menu_contracts_send",
                    "iconCls" => "fa fa-envelope-o",
                    "type" => "get"
                )
            )
        ),
        array(
            "id" => "actions-title",
            "heading" => array( "label" => "Outils" )
        ),
        array(
            "id" => "calendar-link",
            "target" => "calendar_all",
            "label" => "messages.calendar_link",
            "iconCls" => "fa fa-calendar-o",
            "type" => "get"
        ),
        array(
            "id" => "mailbox-link",
            "target" => "mailbox_all",
            "label" => "messages.mailbox_link",
            "iconCls" => "fa fa-envelope-o",
            "type" => "get"
        )
    );

    /**
    * @override
    */
    protected function _setItems($items,$parent=null)
    {

        $translator = Registry::get("container")->get("translator");

        $datas = $this->datas ?:array();

        $get_uid = function(){ return dechex(mt_rand(0xf000,0xffff)); };
        
        for($j=0;$j<sizeof($items);$j++){

            $item = $items[$j];

            if(isset($item["label"])){
                $item["label"] = $translator->trans($item["label"]);
            }

            if(isset($item["target"]) && isset($datas[$item["target"]])){
                $item = array_merge_recursive($item,$datas[$item["target"]]);
                unset($item["target"]);
            }

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

            }

            $items[$j] = $item;

        }

        return $items;

    }
    
}
    