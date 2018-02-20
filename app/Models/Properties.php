<?php

namespace App\Models;

use Framework\DateMethods as DateMethods;

use App\Auth\Auth as Auth;

use App\Models\Ingoing;

class Properties extends \Framework\Model
{

    /**
    * @read
    */
    protected $_withLocal = true;

    /**
    * @read
    */
    protected $_dependencies = array();

    /**
    * @readwrite
    */
    protected $_auth;

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id
    */
    protected $_id_prop;

    protected function getAuth()
    {
        if(empty($this->_auth)){
            $this->_auth = new Auth();
        }
        return $this->_auth->user();
    }
    
    /**
    * @override
    */
    public function delete($where=array())
    {
        if(!empty($this->_raw)){
            $col_name = $this->primaryColumn["name"];
            if(!empty($col_name)){
                $_id = $this->{$col_name};
            }
        }
        if(isset($_id)){
            $t_where = array("{$col_name} = ?" => $_id);
            for($i=0;$i<sizeof($this->_dependencies);$i++){
                $class = $this->_dependencies[$i];
                $class::deleteAll($t_where);
            }
            Ingoing::deleteAll(array(
                "id_ref = ?" => $_id,
                "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_PROPERTY
            ));
            return parent::delete($where);
        }
        return false;
    }
    
    /**
    * @override
    */
    public function insert()
    {
        $client = $this->auth->model;
        $insertId = parent::insert();
        if($insertId>0){
            $ingoing = (new Ingoing( array( "data" => array( 
                "id_cli" => $client->id_cli,
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
    * @label id property type
    * @validate pattern([2-4])
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
    * @belongto countries.id_cty::countries.-
    */
    protected $_id_cty;
        
    /**
    * @column
    * @readwrite
    * @type date
    * @label comment
    */
    protected $_datein;

    public function getDatein()
    {
        $value = $this->_datein;
        return !empty($value) && false===strpos($value,"0000") ? $value : DateMethods::now();
    }

    /**
    * @column
    * @readwrite
    * @type date
    * @label comment
    */
    protected $_datemod;

    public function getDatemod()
    {
        $value = $this->_datemod;
        return !empty($value) && false===strpos($value,"0000") ? $value : DateMethods::now();
    }

    /**
    * @column
    * @readwrite
    * @type text
    * @label comment
    */
    protected $_comment;

}