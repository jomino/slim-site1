<?php

namespace App\Models;

class Calevents extends \Framework\Model
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
        protected $_id_cev;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id calendar
        * @validate required
        */
        protected $_id_cal;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id cevtypes
        * @belongto cevtypes.id_cevtype::cevtypes.ref_cevtype
        */
        protected $_id_cevtype;
        
        /**
        * @column
        * @readwrite
        * @label title
        * @type text
        */
        protected $_title;
        
        /**
        * @column
        * @readwrite
        * @label description
        * @type text
        */
        protected $_description;

        /**
        * @column
        * @readwrite
        * @type date
        * @label start
        */
        protected $_start;

        /**
        * @column
        * @readwrite
        * @type date
        * @label end
        */
        protected $_end;
    
}