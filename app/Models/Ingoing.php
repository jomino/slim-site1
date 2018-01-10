<?php

namespace App\Models;

class Ingoing extends \Framework\Model
{

    /**
    * @read
    */
    protected $_withLocal = false;

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id
    * @validate required
    */
    protected $_id_ingo;
        
    /**
    * @column
    * @readwrite
    * @type integer
    * @label id category
    * @belongto category.id_cat::category.ref_cat
    */
    protected $_id_cat;
        
    /**
    * @column
    * @readwrite
    * @type integer
    * @label id follow id_cat
    */
    protected $_id_ref;
        
    /**
    * @column
    * @readwrite
    * @type integer
    * @label id clients
    * @belongto clients.id_cli::clients.ref_cli
    */
    protected $_id_cli;
}
