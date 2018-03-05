<?php

namespace App\Models;

class Cevtypes extends \Framework\Model
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
        */
        protected $_id_cevtype;
    
        /**
        * @column
        * @readwrite
        * @label ref
        * @type text
        */
        protected $_ref_cevtype;
    
}