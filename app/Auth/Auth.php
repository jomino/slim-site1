<?php

namespace App\Auth
{
    use Framework\ArrayMethods as ArrayMethods;
    use Framework\StringMethods as StringMethods;

    use App\Models\Clients;
    use App\Auth\Client;
    
    class Auth extends \Framework\Auth
    {

        protected $client;

        protected $model;
        
        /**
         * Fetch a user by ID
         * 
         * @param int $id
         * @return Jasny\Auth\User
         */
        public function fetchUserById($id)
        {
            if(empty($this->client))
            {
                $where = array( "id_cli = ?" => $id );
                $this->client = $this->_getAuth($this->_getClient($where));
            }
            return $this->client;
        }
    
        /**
         * Fetch a user by username
         * 
         * @param string $username
         * @return Jasny\Auth\User
         */
        public function fetchUserByUsername($username)
        {
            
            if(empty($this->client))
            {
                $where = array( "log = ?" => $username );
                $this->client = $this->_getAuth($this->_getClient($where));
                
            }
            return $this->client;
        }

        private function _getAuth($model=null)
        {
            if(!is_null($model)){
                return new Client($model);
            }
            return null;
        }

        private function _getClient($where)
        {
            if(empty($this->model)){
                $this->model = Clients::first($where);
            }
            return $this->model;
        }
    }

}