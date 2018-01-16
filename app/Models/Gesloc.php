<?php

namespace App\Models;

use App\Auth\Auth as Auth;

use App\Models\Ingoing;

class Gesloc extends \Framework\Model
{

    /**
    * @read
    */
    protected $_withLocal = false;

    /**
    * @readwrite
    */
    protected $_auth;

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id contract
    */
    protected $_idgesloc;

    protected function getAuth()
    {
        if(empty($this->_auth)){
            $this->_auth = new Auth();
        }
        return $this->_auth;
    }
    
    /**
    * @override
    */
    public function insert()
    {
        $client = $this->auth->user()->model;
        $insertId = parent::insert();
        if($insertId>0){
            $ingoing = (new Ingoing( array( "data" => array( 
                "id_cli" => $client->id_cli,
                "id_cat" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT,
                "id_ref" => $insertId
            ))))->insert();
        }
        return $insertId;
    }

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id user owner
    * @belongto users.id_ref::-
    */
    protected $_idpro;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id user tenant
    * @belongto users.id_ref::-
    */
    protected $_idloc;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id user syndic
    * @belongto users.id_ref::-
    */
    protected $_idges;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id property
    * @belongto properties.id_ref::-
    */
    protected $_idbien;
    
    /**
    * @column
    * @readwrite
    * @label uri system
    * @type text
    * @length 255
    */
    protected $_agence;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id type assur 
    * @belongto atypes.id_atype::atypes.ref_atype
    */
    protected $_assur;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id type versement
    * @belongto vptypes.id_vptype::vptypes.ref_vptype
    */
    protected $_versepro;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label indexer
    */
    protected $_indexer;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label en débit
    */
    protected $_endebit;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label send charges
    */
    protected $_send_charges;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label skip charges
    */
    protected $_skip_charges;
    
    /**
    * @column
    * @readwrite
    * @label prix
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_prix;
    
    /**
    * @column
    * @readwrite
    * @label charges locative
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_charges;
    
    /**
    * @column
    * @readwrite
    * @label start indice
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_indice;
    
    /**
    * @column
    * @readwrite
    * @label start price
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_prix_debu;
    
    /**
    * @column
    * @readwrite
    * @label start charges
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_charges_debu;
    
    /**
    * @column
    * @readwrite
    * @label ref client
    * @type text
    * @length 255
    */
    protected $_ref;
    
    /**
    * @column
    * @readwrite
    * @label remark client
    * @type text
    */
    protected $_rem;
    
    /**
    * @column
    * @readwrite
    * @label ref bail
    * @type text
    * @length 128
    */
    protected $_ref_bail;
    
    /**
    * @column
    * @readwrite
    * @label ref state
    * @type text
    * @length 128
    */
    protected $_ref_etat;

    /**
    * @column
    * @readwrite
    * @type date
    * @label sign date
    */
    protected $_dt_sign;

    /**
    * @column
    * @readwrite
    * @type date
    * @label start date
    */
    protected $_dt_debu;

    /**
    * @column
    * @readwrite
    * @type date
    * @label end date
    */
    protected $_dt_fin;

    /**
    * @column
    * @readwrite
    * @type date
    * @label index date
    */
    protected $_dt_index;

    /**
    * @column
    * @readwrite
    * @type date
    * @label send date
    */
    protected $_dt_send;

    /**
    * @column
    * @readwrite
    * @type date
    * @label stop date
    */
    protected $_dt_stop;

    /**
    * @column
    * @readwrite
    * @type date
    * @label entry date
    */
    protected $_dt_entr;

    /**
    * @column
    * @readwrite
    * @type date
    * @label pay date
    */
    protected $_dt_pay;
    
}