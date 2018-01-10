<?php

namespace App\Models;

use App\Auth\Auth as Auth;

use App\Models\Ingoing;

class Properties extends \Framework\Model
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
    */
    protected $_id_prop;
    
    /**
    * @override
    */
    public function insert()
    {
        $auth = new Auth();
        $client = $auth->user()->model;
        $insertId = parent::insert();
        if($insertId>0){
            $ingoing = (new Ingoing( array( "data" => array( 
                "id_cli" => $client->getRaw()->id_cli,
                "id_cat" => \App\Statics\Models::CATEGORY_TYPE_PROPERTY,
                "id_ref" => $insertId
            ))))->insert();
        }
        return $insertId;
    }

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id lang
    * @validate pattern([2-3])
    * @belongto ptypes.id_ptype::ptypes.ref_ptype
    */
    protected $_id_ptype;

    /**
    * @column
    * @secondary !important
    * @readwrite
    * @type text
    * @label id ref affiliate
    * @validate required
    * @comment uid partners
    */
    protected $_id_ref;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label price
    * @validate required
    * @validate min(1)
    */
    protected $_price;

    /**
    * @column
    * @readwrite
    * @type text
    * @label 
    */
    protected $_name;

    /**
    * @column
    * @readwrite
    * @type text
    * @label street
    */
    protected $_street;

    /**
    * @column
    * @readwrite
    * @type text
    * @label street num
    */
    protected $_num;

    /**
    * @column
    * @readwrite
    * @type text
    * @label street cp
    */
    protected $_cp;

    /**
    * @column
    * @readwrite
    * @type text
    * @label street city
    */
    protected $_ville;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id countries
    * @belongto countries.id_cty::countries.ref_cty
    */
    protected $_id_cty;
        
    /**
    * @column
    * @readwrite
    * @type date
    * @label comment
    */
    protected $_datein;

    /**
    * @column
    * @readwrite
    * @type date
    * @label comment
    */
    protected $_datemod;

    /**
    * @column
    * @readwrite
    * @type text
    * @label comment
    */
    protected $_comment;

}