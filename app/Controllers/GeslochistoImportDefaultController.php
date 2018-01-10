<?php

namespace App\Controllers;

use \App\Models\Geslochisto;
use \App\Models\GeslochistoDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\GeslochistoDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class GeslochistoImportDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;

        $records = Geslochisto::sync( new GeslochistoDefaults( array(
            "interface" => new Interfaces( array(
                "request" => GeslochistoDefaultsRequest::class,
                "response" => DefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "agence" => $client->uri
            )
        ));

        $response_data = array(
            "success" => true,
            "updated" => sizeof($records)
        );
        
        return $response->withJson(json_encode($response_data));
        
    }

}
