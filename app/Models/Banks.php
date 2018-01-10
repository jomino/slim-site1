<?php

namespace App\Models;

class Banks extends \Framework\Model
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
        * @label id
        * @validate required
        */
        protected $_id_bank;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label range from
        * @validate required
        */
        protected $_from;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label range to
        * @validate required
        */
        protected $_to;
        
        /**
        * @column
        * @readwrite
        * @label BIC ref
        * @type text
        */
        protected $_bic;
        
        /**
        * @column
        * @readwrite
        * @label name
        * @type text
        */
        protected $_name;
    
}