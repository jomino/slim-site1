<?php

namespace App\Models;

use Framework\StringMethods as StringMethods;
use Framework\Model\Exception as Exception;

use App\Statics\Models as STATICS;

class GeslocDefaults extends \Framework\Model
{

    /**
    * @read
    * @override
    */
    protected $_withLocal = false;

    /**
    * @override
    */
    public function getConnector()
    {
        if (empty($this->_connector))
        {
            $connector = new \App\Models\Interfaces\DefaultsConnector(array(
                "interface" => $this->interface
            ));
            if (!$connector)
            {
                throw new Exception\Connector("No connector !");
            }
            $this->_connector = $connector;
        }
        return $this->_connector;
    }

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    */
    protected $_idgesloc;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_idpro;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_idloc;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_idges;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_idbien;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_agence;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_assur;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_versepro;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_indexer;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_endebit;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_send_charges;

    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_skip_charges;
    
    /**
    * @column
    * @readwrite
    * @type decimal
    */
    protected $_prix;
    
    /**
    * @column
    * @readwrite
    * @type decimal
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
    * @type decimal
    */
    protected $_prix_debu;
    
    /**
    * @column
    * @readwrite
    * @type decimal
    */
    protected $_charges_debu;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_ref;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_rem;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_ref_bail;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_ref_etat;

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_sign;

    public function getDt_sign()
    {
        $value = $this->_dt_sign;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_debu;

    public function getDt_debu()
    {
        $value = $this->_dt_debu;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_fin;

    public function getDt_fin()
    {
        $value = $this->_dt_fin;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_index;

    public function getDt_index()
    {
        $value = $this->_dt_index;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_send;

    public function getDt_send()
    {
        $value = $this->_dt_send;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_stop;

    public function getDt_stop()
    {
        $value = $this->_dt_stop;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_entr;

    public function getDt_entr()
    {
        $value = $this->_dt_entr;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_pay;

    public function getDt_pay()
    {
        $value = $this->_dt_pay;
        return strpos($value,"0000")===false ? $value:null;
    }
}