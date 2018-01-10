<?php

namespace App\Controllers;

use \App\Models\Gesloc;
use \App\Models\Ingoing;
use \App\Models\GeslocDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\GeslocDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class GeslocImportDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;

        $records = Gesloc::sync( new GeslocDefaults( array(
            "interface" => new Interfaces( array(
                "request" => GeslocDefaultsRequest::class,
                "response" => DefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "agence" => $client->getRaw()->uri
            )
        ));

        $response_data = array(
            "success" => true,
            "updated" => sizeof($records)
        );
        
        return $response->withJson(json_encode($response_data));
        
    }

}
