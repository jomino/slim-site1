<?php

namespace App\Controllers;

use \App\Models\Users;
use \App\Models\Ingoing;

class UsersDeleteDefaultController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {

        if(!empty($args["id"])){
            $where = array( "id_ref = ?" => (int)$args["id"] );
            Users::deleteAll($where);
            $count_ingo = 1;
        }else{

            $client = $this->client->model;

            $ingo = Ingoing::all(array(
                "id_cli = ?" => $client->id_cli,
                "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_USERS
            ));

            $count_ingo = 0;

            if(!empty($ingo)){
                $count_ingo = sizeof($ingo);
                for($i=0;$i<$count_ingo;$i++){
                    $id_user = $ingo[$i]->getRaw()->id_ref;
                    $user = Users::first(array(
                        "id_user = ?" => $id_user
                    ));
                    if(!empty($user)){
                        $user->delete();
                    }
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
