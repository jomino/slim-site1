<?php

namespace App\Models;

class Contacts extends \Framework\Model
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
        * @label id contacts
        * @validate required
        */
        protected $_id_ctc;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id users
        * @validate required
        * @belongto users.id_user::-
        */
        protected $_id_user;
        
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id ctypes
        * @belongto ctypes.id_ctype::ctypes.ref_ctype
        */
        protected $_id_ctype;
                
        /**
        * @column
        * @readwrite
        * @label ref
        * @type text
        * @length 128
        */
        protected $_contact;
    
}