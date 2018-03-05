<?php

namespace App\Models;

class Calendars extends \Framework\Model
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
        protected $_id_cal;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id client
        * @validate required
        */
        protected $_id_cli;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id caltypes
        * @belongto caltypes.id_caltype::caltypes.ref_caltype
        */
        protected $_id_caltype;
        
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
    
}