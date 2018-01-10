<?php

namespace App\Models;

class Test extends \Framework\Model
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
        protected $_id;
    
        /**
        * @column
        * @readwrite
        * @label test
        * @type text
        */
        protected $_data;
    
}