<?php

namespace App\Controllers;

use \App\Models\Gesloc;
use \App\Models\Geslocpay;
use \App\Models\Ingoing;

class GeslocDeleteDefaultController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {

        $client = $this->client->model;

        $result = Ingoing::all(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT
        ));

        $count_ingo = 0;

        if(!empty($result)){

            for($i=0;$i<sizeof($result);$i++){

                $id_prop = $result[$i]->id_ref;

                $contract = Gesloc::first(array(
                    "idgesloc = ?" => $id_prop
                ));

                if(!empty($contract)){

                    if($contract->delete()){

                        $count_ingo++;

                        //-- debug
                        $this->logger->debug("success_delete:",["model"=>"Gesloc","origine"=>"ingoing","origineID"=>"{$id_prop}"]);
                        //--
                    }else{
                        //-- debug
                        $this->logger->error("error_delete:",["model"=>"Gesloc","origine"=>"ingoing","origineID"=>"{$id_prop}"]);
                        //--
                    }
                    
                }else{
                    //-- debug
                    $this->logger->error("error_delete: phantom record",["model"=>"Gesloc","origine"=>"ingoing","origineID"=>"{$id_prop}"]);
                    //--
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
