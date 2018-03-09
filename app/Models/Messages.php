<?php

namespace App\Models;

class Messages extends \Framework\Model
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
        protected $_id_msg;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id client
        */
        protected $_id_cli;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id msgtypes
        * @belongto msgtypes.id_msgtype::msgtypes.ref_msgtype
        */
        protected $_id_msgtype;
        
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
        protected $_message;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label message read
        * @comment values[0/1]
        */
        protected $_read;

        /**
        * @column
        * @readwrite
        * @type date
        * @label recieved
        */
        protected $_received;

        /**
        * @column
        * @readwrite
        * @type date
        * @label respond
        */
        protected $_respond;
    
}