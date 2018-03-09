<?php

namespace App\Models;

class Msgcls extends \Framework\Model
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
        protected $_id_cls;
    
        /**
        * @column
        * @readwrite
        * @label ref message
        * @type text
        */
        protected $_ref_cls;
    
}