<?php

namespace App\Models;

class Geslocdoc extends \Framework\Model
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
    * @label document id
    */
    protected $_iddoc;
    
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
    * @label client uri
    * @type text
    */
    protected $_agence;
        
    /**
    * @column
    * @readwrite
    * @type integer
    * @label id document type
    * @belongto dtypes.id_dtype::dtypes.ref_dtype
    */
    protected $_doctype;

    /**
    * @column
    * @readwrite
    * @type date
    * @label entry date
    */
    protected $_dt_in;

    public function getDt_in()
    {
        $value = $this->_dt_in;
        return !empty($value) && false===strpos($value,"0000") ? $value:null;
    }
    
    /**
    * @column
    * @readwrite
    * @label filedatas
    * @type text
    */
    protected $_filedatas;

}