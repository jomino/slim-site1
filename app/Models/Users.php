<?php

namespace App\Models;

use App\Auth\Auth as Auth;

use App\Models\Contacts;
use App\Models\Comptes;
use App\Models\Ingoing;

class Users extends \Framework\Model
{

    /**
    * @read
    */
    protected $_withLocal = true;

    /**
    * @readwrite
    */
    protected $_auth;

    /**
    * @read
    */
    protected $_dependencies = array(
        Contacts::class,
        Comptes::class
    );
    
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
                "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_USERS
            ));
            return parent::delete($where);
        }
        return false;
    }

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
    public function insert()
    {
        $client = $this->auth->model;
        $insertId = parent::insert();
        if($insertId>0){
            $ingoing = (new Ingoing( array( "data" => array( 
                "id_cli" => $client->id_cli,
                "id_cat" => \App\Statics\Models::CATEGORY_TYPE_USERS,
                "id_ref" => $insertId
            ))))->insert();
        }
        return $insertId;
    }

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id
    */
    protected $_id_user;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id lang
    * @belongto lang.id_lang::lang.ref_lang
    */
    protected $_id_lang;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id civil
    * @belongto civil.id_civil::civil.ref_civil
    */
    protected $_id_civil;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id etatciv
    * @belongto etatciv.id_eciv::etatciv.ref_eciv
    */
    protected $_id_eciv;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id status
    * @belongto status.id_stat::status.ref_stat
    */
    protected $_id_stat;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id utypes
    * @belongto utypes.id_utype::utypes.ref_utype
    */
    protected $_id_utype;

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
    * @type text
    * @label 
    */
    protected $_pnom;

    /**
    * @column
    * @readwrite
    * @type text
    * @label 
    */
    protected $_nom;

    /**
    * @column
    * @readwrite
    * @type text
    * @label societe
    */
    protected $_soc;

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
    * @label ref countries
    * @belongto countries.id_cty::countries.-
    */
    protected $_id_cty;
    
    /**
    * @column
    * @readwrite
    * @type text
    * @label born city
    */
    protected $_wborn;

    /**
    * @column
    * @readwrite
    * @type text
    * @label born country
    */
    protected $_natio;

    /**
    * @column
    * @readwrite
    * @type text
    * @label national number
    */
    protected $_numnat;

    /**
    * @column
    * @readwrite
    * @type text
    * @label comment
    */
    protected $_comment;

    /**
    * @column
    * @readwrite
    * @type date
    * @label birthday
    */
    protected $_born;

    public function getBorn()
    {
        $value = $this->_born;
        return !empty($value) ? $value:"";
    }

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
        return !empty($value) ? $value:"";
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
        $dt = new \DateTime("now", new \DateTimeZone("Europe/Brussels"));
        return !empty($value) ? $value:$dt->format("Y-m-d H:i:s");
    }


}