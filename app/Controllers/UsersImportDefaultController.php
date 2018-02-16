<?php

namespace App\Controllers;

use \App\Models\Users;
use \App\Models\UsersDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\UsersDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class UsersImportDefaultController extends \Core\Controller
{
    
    private $_pack = 50;
    private $_offset = 0;
    private $_count = 0;
    private $_uri;
    
    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;
        $this->_uri = $client->uri;

        while($_n=$this->_import()){
            $this->_count += $_n;
            if($_n<$this->_pack){
                //$this->logger->debug("Imported records :",["count"=>$this->_count]);
                break;
            }
            $this->_offset += $this->_pack;
        }

        $response_data = array(
            "success" => true,
            "imported" => $this->_count
        );
        
        return $response->withJson(json_encode($response_data));
    }

    private function _import()
    {
        return sizeof( Users::sync( new UsersDefaults( array(
            "interface" => new Interfaces( array(
                "request" => UsersDefaultsRequest::class,
                "response" => DefaultsResponse::class
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
