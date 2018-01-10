<?php

namespace App\Controllers;

use \App\Models\Properties;
use \App\Models\PropertiesDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\PropertiesDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class PropertiesImportDefaultController extends \Core\Controller
{
    
    private $_count = 0;
    private $_uri;
    
    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;
        $this->_uri = $client->uri;

        $this->_count = $this->_import();

        $response_data = array(
            "success" => true,
            "imported" => $this->_count
        );
        
        return $response->withJson(json_encode($response_data));
    }

    private function _import()
    {
        return sizeof( Properties::sync( new PropertiesDefaults( array(
            "interface" => new Interfaces( array(
                "request" => PropertiesDefaultsRequest::class,
                "response" => DefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "agence" => $this->_uri
            )
        )));
    }

}
