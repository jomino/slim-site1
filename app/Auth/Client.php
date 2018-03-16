<?php

namespace App\Auth
{

    use App\Models\Clients;

    use App\Statics\Models as STATICS;

    class Client extends \Framework\Auth\User
    {
        public $status;

        public $model;
                
        public function __construct($client=null)
        {
            $options = array();
            if($client)
            {
                $options = array(
                    "id" => $client->id,
                    "username" => $client->log,
                    "password" => $client->pwd,
                    "active" => (bool) $client->getBelongTo("id_user.id_stat")==STATICS::STATUS_TYPE_ACTIVE,
                    "status" => $client->getBelongTo("id_user.id_stat.ref_stat"),
                    "model" => $client
                );
            }
            foreach($options as $k=>$v){
                $this->{$k} = $v;
            }
            //var_dump($this);
        }    
    
        /**
        *@override
        */
        public function onLogin()
        {

            if (!$this->active) {
                return false;
            }
        
            $client = $this->model;
            $client->set("connected", 1);
            $client->set("least", date("YmdHis"));
            $client->update();

        }
    
        /**
        *@override
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