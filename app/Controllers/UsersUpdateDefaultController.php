<?php

namespace App\Controllers;

use \App\Models\Users;
use \App\Models\Ingoing;
use \App\Models\UsersDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\UsersDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class UsersUpdateDefaultController extends \Core\Controller
{
    
    private $_pack = 50;
    private $_offset = 0;
    private $_count = 0;
    private $_uri;

    public function __invoke($request, $response, $args)
    {
        if(!empty($args["id"])){
            $user = Users::first(array(
                "id_ref = ?" => (int)$args["id"]
            ));
            if(!empty($user)){
                $user->syncWith( new UsersDefaults( array(
                    "interface" => new Interfaces( array(
                        "request" => UsersDefaultsRequest::class,
                        "response" => DefaultsResponse::class
                    ))
                )), array(
                    "where" => array(
                        "agence" => "www.scriptimmo.com",
                        "filter" => array(
                            "property" => "iduser",
                            "value" => $args["id"]
                        )
                    ),
                    "limit" => array(
                        "max" => 1,
                        "offset" => 0
                    )
                ));
            }
            $this->_count = 1;
        }else{

            $client = $this->client->model;
            
            $this->_uri = $client->uri;

            while($_n=$this->_update()){
                $this->_count += $_n;
                if($_n<$this->_pack){ break; }
                $this->_offset += $this->_pack;
            }

        }

        $response_data = array(
            "success" => true,
            "updated" => $this->_count
        );
        
        return $response->withJson(json_encode($response_data));
        
    }

    private function _update()
    {
        return sizeof( Users::sync( new UsersDefaults( array(
            "interface" => new Interfaces( array(
                "request" => UsersDefaultsRequest::class,
                "response" => UsersDefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "agence" => $this->_uri
            ),
            "limit" => array(
                "max" => $this->_pack,
                "offset" => $this->_offset
            )
        )));
    }
}
