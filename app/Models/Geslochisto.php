<?php

namespace App\Models;

class Geslochisto extends \Framework\Model
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
    * @label historic id
    */
    protected $_idhisto;
    
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
    * @type date
    * @label date entry
    */
    protected $_date;
    
    /**
    * @column
    * @readwrite
    * @label describ
    * @type text
    */
    protected $_rem;

}