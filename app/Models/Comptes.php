<?php

namespace App\Models;

class Comptes extends \Framework\Model
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
        protected $_id_cpt;
    
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
        * @label id users
        * @belongto banks.id_bank::banks.name
        */
        protected $_id_bank;

        /**
        * @column
        * @readwrite
        * @label num. compte
        * @type text
        */
        protected $_compte;
    
}