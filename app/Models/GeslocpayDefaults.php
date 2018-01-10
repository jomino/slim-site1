<?php

namespace App\Models;

use Framework\StringMethods as StringMethods;
use Framework\Model\Exception as Exception;

use App\Statics\Models as STATICS;

class GeslocpayDefaults extends \Framework\Model
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
                throw new Exception\Connector("No connector");
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
    protected $_idpay;
    
    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_idgesloc;
    
    /**
    * @column
    * @readwrite
    * @type integer
    */
    protected $_paytype;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_agence;

    /**
    * @column
    * @readwrite
    * @type decimal
    */
    protected $_debitsum;
    
    /**
    * @column
    * @readwrite
    * @type decimal
    */
    protected $_creditsum;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_refpay;
    
    /**
    * @column
    * @readwrite
    * @type text
    */
    protected $_rem;

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_debit;

    public function getDt_debit()
    {
        $value = $this->_dt_debit;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_credit;

    public function getDt_credit()
    {
        $value = $this->_dt_credit;
        return strpos($value,"0000")===false ? $value:null;
    }

    /**
    * @column
    * @readwrite
    * @type date
    */
    protected $_dt_revers;

    public function getDt_revers()
    {
        $value = $this->_dt_revers;
        return strpos($value,"0000")===false ? $value:null;
    }

}