<?php

namespace App\Models;

class Dtypes extends \Framework\Model
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
        * @validate required
        */
        protected $_id_dtype;
    
        /**
        * @column
        * @readwrite
        * @label ref
        * @type text
        */
        protected $_ref_dtype;
    
}