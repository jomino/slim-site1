<?php

namespace App\Models;

class Category extends \Framework\Model
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
        * @label id category
        * @validate required
        */
        protected $_id_cat;
    
        /**
        * @column
        * @readwrite
        * @label ref category
        * @type text
        */
        protected $_ref_cat;
    
}