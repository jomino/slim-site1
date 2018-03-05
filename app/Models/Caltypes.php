<?php

namespace App\Models;

class Caltypes extends \Framework\Model
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
        protected $_id_caltype;
    
        /**
        * @column
        * @readwrite
        * @label ref
        * @type text
        */
        protected $_ref_caltype;
    
}