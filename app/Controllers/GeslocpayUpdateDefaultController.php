<?php

namespace App\Controllers;

use \App\Models\Ingoing;
use \App\Models\Geslocpay;
use \App\Models\GeslocpayDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\GeslocpayDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

use App\Auth\Auth as Auth;

class GeslocpayUpdateDefaultController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;

        $result = Ingoing::all(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT
        ));

        $count = 0;

        if(!empty($result)){
            for($i=0;$i<sizeof($result);$i++){
                $_id = $result[$i]->id_ref;
                $count += $this->_update($_id);
            }
        }

        $response_data = array(
            "success" => $count>0,
            "updated" => $count
        );
        
        return $response->withJson(json_encode($response_data));
    }

    private function _update($id)
    {
        return sizeof( Geslocpay::sync( new GeslocpayDefaults( array(
            "interface" => new Interfaces( array(
                "request" => GeslocpayDefaultsRequest::class,
                "response" => DefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "idgesloc" => $id
            )
        )));
    }

}
