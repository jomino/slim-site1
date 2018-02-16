<?php

namespace App\Controllers;

use \App\Models\Properties;
use \App\Models\Ingoing;
use \App\Models\PropertiesDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\PropertiesDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class PropertiesUpdateDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;

        $result = Properties::sync( new PropertiesDefaults( array(
            "interface" => new Interfaces( array(
                "request" => PropertiesDefaultsRequest::class,
                "response" => DefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "agence" => $client->uri
            )
        ));

        $response_data = array(
            "success" => true,
            "updated" => sizeof($result)
        );
        
        return $response->withJson(json_encode($response_data));
        
    }

}
