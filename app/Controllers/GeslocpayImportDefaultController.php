<?php

namespace App\Controllers;

use \App\Models\Ingoing;
use \App\Models\Geslocpay;
use \App\Models\GeslocpayDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\GeslocpayDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

use App\Auth\Auth as Auth;

class GeslocpayImportDefaultController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {

        $auth = new Auth();
        $client = $auth->user()->model;

        $ingo = Ingoing::all(array(
            "id_cli = ?" => $client->getRaw()->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT
        ));

        $count = 0;

        if(!empty($ingo)){
            for($i=0;$i<sizeof($ingo);$i++){
                $_id = $ingo[$i]->getRaw()->id_ref;
                $count += $this->_import($_id);
            }
        }

        $response_data = array(
            "success" => $count>0,
            "imported" => $count
        );
        
        return $response->withJson(json_encode($response_data));
    }

    private function _import($id)
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
