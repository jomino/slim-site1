<?php

namespace App\Controllers;

use \App\Models\Geslocdoc;
use \App\Models\GeslocdocDefaults;
use \Framework\Model\Interfaces;
use \App\Models\Interfaces\GeslocdocDefaultsRequest;
use \App\Models\Interfaces\DefaultsResponse;

class GeslocdocImportDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {
        $errors = array();

        $client = $this->client->model;
        
        $ingoings = \App\Models\Ingoing::all(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT
        ));

        $count = 0;

        $local_tpl = $this->settings["loadedfiles"]["path"]."/%s";

        if(!empty($ingoings)){
            for($i=0;$i<sizeof($ingoings);$i++){
                $g_id = $ingoings[$i]->id_ref;
                $records = $this->_import($g_id);
                $count += sizeof($records);
                for($j=0;$j<sizeof($records);$j++){
                    $r_id = $records[$j]->id;
                    $f_datas = json_decode($records[$j]->filedatas,false);
                    $f_contents = $this->_file_get_contents($g_id,$r_id,$f_datas->name);
                    if(!empty($f_contents) && !empty($f_contents["content"])){
                        file_put_contents(sprintf( $local_tpl, $f_contents["name"]), $f_contents["content"]);
                    }else{
                        $errors[] = array(
                            "local" => $f_datas->name,
                            "distant" => isset($f_contents["name"]) ? $f_contents["name"]:"?",
                            "error" => "file_not_found"
                        );
                    }
                }
            }
        }

        $response_data = array(
            "success" => sizeof($errors)==0,
            "inserted" => $count,
            "errors" => $errors
        );
        
        return $response->withJson(json_encode($response_data));
        
    }

    private function _import($idgesloc)
    {
        return Geslocdoc::sync( new GeslocdocDefaults( array(
            "interface" => new Interfaces( array(
                "request" => GeslocdocDefaultsRequest::class,
                "response" => DefaultsResponse::class
            ))
        )), array(
            "where" => array(
                "idgesloc" => $idgesloc
            )
        ));
    }

    private function _file_get_contents($g_id,$r_id,$f_name)
    {
        $client = $this->client->model;
        $f_tpl = "GESDOC_%s_%s.%s";
        $u_tpl = "http://%s/photo/%s";
        $f_exts = explode(".",$f_name);
        $f_id1 = str_pad($g_id, 4, "0", STR_PAD_LEFT); 
        $f_id2 = str_pad($r_id, 4, "0", STR_PAD_LEFT);
        $f_ext = $f_exts[sizeof($f_exts)-1];
        $f_name = sprintf( $f_tpl, $f_id1, $f_id2, $f_ext);
        $f_url = sprintf( $u_tpl, $client->uri, $f_name);
        return array(
            "name" => $f_name,
            "content" => file_get_contents($f_url)
        );
    }

}
