<?php

namespace App\Models;

class Clients extends \Framework\Model
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
        protected $_id_cli;
    
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
        * @label id groups
        * @belongto groups.id_grp::groups.ref_grp
        */
        protected $_id_grp;
        
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id levels
        * @belongto levels.id_lvl::levels.ref_lvl
        */
        protected $_id_lvl;
        
        /**
        * @column
        * @readwrite
        * @type integer
        * @label id client type
        * @belongto clitypes.id_clit::clitypes.ref_clit
        */
        protected $_id_clit;
                                
        /**
        * @column
        * @readwrite
        * @label connected state
        * @type integer
        */
        protected $_connected;
        
        /**
        * @column
        * @readwrite
        * @label uri system
        * @type text
        * @length 128
        */
        protected $_uri;
        
        /**
        * @column
        * @readwrite
        * @label login
        * @type text
        * @length 128
        */
        protected $_log;
                                            
        /**
        * @column
        * @readwrite
        * @label password
        * @type text
        * @length 32
        */
        protected $_pwd;
                                
        /**
        * @column
        * @readwrite
        * @label date connection
        * @type text
        * @length 128
        */
        protected $_least;

        public function getLeast()
        {
            $value = $this->_least;
            return !empty($value) ? $value:"";
        }
                
}