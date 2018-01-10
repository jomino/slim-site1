<?php

namespace App\Controllers;

use \App\Models\Properties;
use \App\Models\Ingoing;

class PropertiesDeleteDefaultController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;

        $ingo = Ingoing::all(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_PROPERTIES
        ));

        $count_ingo = 0;

        if(!empty($ingo)){
            $count_ingo = sizeof($ingo);
            for($i=0;$i<$count_ingo;$i++){
                $id_prop = $ingo[$i]->getRaw()->id_ref;
                $property = Properties::first(array(
                    "id_prop = ?" => $id_prop
                ));
                if(!empty($property)){
                    $property->delete();
                }
            }
        }

        $response_data = array(
            "success" => true,
            "deleted" => $count_ingo
        );
        
        return $response->withJson(json_encode($response_data));

    }
}
