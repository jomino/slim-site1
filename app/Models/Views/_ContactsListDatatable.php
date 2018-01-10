<?php

namespace App\Models\Views;

use Framework\Registry as Registry;

class ContactsListDatatable extends \Framework\ViewModel
{

    /**
    * @read
    */
    protected $_map = array(
        "contacts" => array(
            array(
                "type" => "field",
                "field" => "id_user",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'id_user'", // mandatory !!
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "pnom",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'pnom'", // mandatory !!
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "num",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'num'", // mandatory !!
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "cp",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'cp'", // mandatory !!
                    "visible" => "false"
                )
            ),
            array(
                "type" => "field",
                "field" => "ville",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'ville'", // mandatory !!
                    "visible" => "false"
                )
            ),
            array(
                "index" => 0,
                "type" => "field",
                "label" => "{spRef}",
                "field" => "id_ref",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'id_ref'"
                )
            ),
            array(
                "index" => 1,
                "type" => "field",
                "field" => "nom",
                "label" => "{spName}",
                "ordering" => "ASC",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'nom'",
                    "render" => "$.jo.datatableRenderer(1)"
                )
            ),
            array(
                "index" => 2,
                "type" => "field",
                "field" => "street",
                "label" => "{spAddress}",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'street'",
                    "render" => "$.jo.datatableRenderer(2)"
                )
            ),
            array(
                "index" => 3,
                "type" => "field",
                "label" => "{spPhone}",
                "field" => "phone",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'phone'"
                )
            ),
            array(
                "index" => 4,
                "type" => "field",
                "label" => "{spEmail}",
                "field" => "email",
                "column" => array(
                    "searchable" => "false",
                    "orderable" => "false",
                    "data" => "'email'"
                )
            )
        )
    );
    
}