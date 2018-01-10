<?php

namespace App\Models;

class Geslocpay extends \Framework\Model
{

    /**
    * @read
    */
    protected $_withLocal = true;

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id payement
    */
    protected $_idpay;
    
    /**
    * @column
    * @readwrite
    * @type integer
    * @label id contract
    */
    protected $_idgesloc;
    
    /**
    * @column
    * @readwrite
    * @type integer
    * @label type_de_payement
    * @validate gt(0)
    * @belongto gptypes.id_gptype::gptypes.ref_gptype
    */
    protected $_paytype;
    
    /**
    * @column
    * @readwrite
    * @label client uri
    * @type text
    */
    protected $_agence;

    /**
    * @column
    * @readwrite
    * @label debit
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_debitsum;
    
    /**
    * @column
    * @readwrite
    * @label credit
    * @type decimal
    * @validate numeric(10,2)
    */
    protected $_creditsum;

    /**
    * @column
    * @readwrite
    * @type date
    * @label debit date
    */
    protected $_dt_debit;

    /**
    * @column
    * @readwrite
    * @type date
    * @label credit date
    */
    protected $_dt_credit;

    /**
    * @column
    * @readwrite
    * @type date
    * @label owner pay date
    */
    protected $_dt_revers;
    
    /**
    * @column
    * @readwrite
    * @label ref. payement
    * @type text
    */
    protected $_refpay;
    
    /**
    * @column
    * @readwrite
    * @label remark client
    * @type text
    */
    protected $_rem;
}