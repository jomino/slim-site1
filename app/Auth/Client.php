<?php

namespace App\Auth
{

    use App\Models\Clients;

    class Client extends \Framework\Auth\User
    {
        public $status;

        public $model;
                
        public function __construct($client=null)
        {
            $options = array();
            if($client)
            {
                $status = $client->getBelongTo("id_user.id_stat.ref_stat");
                $options = array(
                    "id" => $client->getRaw()->id_cli,
                    "username" => $client->getRaw()->log,
                    "password" => $client->getRaw()->pwd,
                    "active" => true,
                    "status" => $status,
                    "model" => $client
                );
            }
            foreach($options as $k=>$v){
                $this->{$k} = $v;
            }
            //var_dump($this);
        }    
    
        /**
         * Event called on login.
         * 
         * @return boolean  false cancels the login
         */
        public function onLogin()
        {

            $client = $this->model;
            $client->set("connected", 1);
            $client->set("least", date("YmdHis"));
            
            $client->update();

        }
    
        /**
         * Event called on logout.
         */
        public function onLogout()
        {

            $client = $this->model;
            $client->set("connected", 0);
            $client->set("least", date("YmdHis"));
            
            $client->update();

        }
    }
}