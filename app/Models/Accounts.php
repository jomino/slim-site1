<?php

namespace App\Models;

class Accounts extends \Framework\Model
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
        * @label id client
        * @validate required
        */
        protected $_id_acc;
    
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id client
        * @belongto clients.id_cli::-
        */
        protected $_id_cli;

        /**
        * @column
        * @readwrite
        * @type integer
        * @label id category
        * @belongto category.id_cat::category.ref_cat
        */
        protected $_id_cat;
        
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id account type
        * @belongto actypes.id_actype::-
        */
        protected $_id_actype;
                
}