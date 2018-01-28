<?php

namespace App\Models;

class Countries extends \Framework\Model
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
        protected $_id_cty;
    
        /**
        * @column
        * @readwrite
        * @type text
        * @label code iso
        */
        protected $_ref_cty;
        
        /**
        * @column
        * @readwrite
        * @label frendly name
        * @type text
        */
        protected $_name;
    
}