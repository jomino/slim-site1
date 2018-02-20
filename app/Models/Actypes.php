<?php

namespace App\Models;

class Actypes extends \Framework\Model
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
        * @label id
        * @comment 0 based !important
        */
        protected $_id_actype;
    
        /**
        * @column
        * @readwrite
        * @label ref
        * @type text
        */
        protected $_ref_actype;
        
        /**
        * @column
        * @readwrite
        * @type integer
        * @label limit use
        */
        protected $_seats;
    
}