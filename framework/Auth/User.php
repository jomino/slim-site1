<?php

namespace Framework\Auth
{

    class User implements \Jasny\Auth\User
    {
        /**
         * @var int
         * @readwrite
         */
        protected $id;
    
        /**
         * @var string
         * @readwrite
         */
        protected $username;
    
        /**
         * Hashed password
         * @var string
         * @readwrite
         */
        protected $password;
    
        /**
         * @var boolean
         * @readwrite
         */
        protected $active;

        /**
         * Get user id
         * 
         * @return int|string
         */
        public function getId()
        {
            return $this->id;
        }
        
        /**
         * Get user's username
         * 
         * @return string
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * Get the hashed password
         * 
         * @return string
         */
        public function getHashedPassword()
        {
            return password_hash($this->password,PASSWORD_DEFAULT);
        }
    
    
        /**
         * Event called on login.
         * 
         * @return boolean  false cancels the login
         */
        public function onLogin()
        {
            if (!$this->active) {
                return false;
            }
    
            // You might want to log the login
        }
    
        /**
         * Event called on logout.
         */
        public function onLogout()
        {
            // You might want to log the logout
        }
    }
}