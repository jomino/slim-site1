<?php

namespace App\Models;

class Gptypes extends \Framework\Model
{
    
        /**
        *@read
        */
        protected $_withLocal = true;
    
        /**
        *@column
        *@primary
        *@readwrite
        *@type integer
        *@label id
        */
        protected $_id_gptype;
    
        /**
        *@column
        *@readwrite
        *@label ref
        *@type text
        */
        protected $_ref_gptype;
    
}