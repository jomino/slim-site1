<?php

namespace App\Controllers;

use Framework\ArrayMethods as ArrayMethods;
use Framework\DateMethods as DateMethods;

use App\Statics\Models as STATICS;

class BodyDefaultMsgController extends \Core\Controller
{
    private $views_path = "Default/App/Content";

    public function home($request, $response, $args)
    {
        $router = $this->router;
        $assets = $this->assets;
        $translator = $this->translator;

        $table_id = "dt-contacts";

        $script_datas = array();

        // global    
        $script_datas["table_id"] = $table_id;
        $script_datas["table_hdl"] = $router->pathFor('mailbox_pipe');

        $model = new \App\Models\Views\MessagesListFootable(array(
            "data" => array(
                "action_message_checked" => array(
                    "action" => array(
                        "action-message-checked"
                    )
                ),
                "action_message_read" => array(
                    "action" => array(
                        "action-message-read"
                    )
                ),
                "action_message_attach" => array(
                    "action" => array(
                        "action-message-attach"
                    )
                ),
                "action_message_date" => array(
                    "action" => array(
                        "action-message-date"
                    )
                )
            )
        ));

        $script_datas["table_defs"] = $model->getColumns();

        $script_datas["table_scripts"] = array_merge(
            $assets->getPaths("footable_lib","css","vendor"),
            $assets->getPaths("footable_lib","js","vendor")
        );

        return $this->view->render( $response, "{$this->views_path}/Messages/footable-bs.html.twig", $script_datas);
        
    }

    public function pipe($request, $response, $args)
    {

        $client = $this->client->model;

        $viewmodel = new \App\Models\Views\MessagesListFootable();

        $where = array(
            "messages.id_cli = ?" => $client->id_cli,
            "contacts.id_ctype = ?" => STATICS::CONTACT_TYPE_EMAIL
        );

        $model = new \App\Models\Messages();

        $query = $model->connector->query()
            ->from($model->table,["messages.*"]);

        foreach($where as $k=>$v){
            $query->where($k,$v);
        }

        $query->join("contacts","contacts.id_user=messages.id_user",["contacts.contact"]);

        $query->order("messages.read","ASC");

        $valid_recs = $query->all();

        $response_datas = array();

        $total_records = $filtered_records = sizeof($valid_recs);

        if(!empty($valid_recs)){

            $model = new \App\Models\Views\MessagesListFootable();
            $col_models = $model->getMap();

            for($j=0;$j<sizeof($valid_recs);$j++){

                $t_resp = array();
                
                $u_rec = $valid_recs[$j];

                for($i=0;$i<sizeof($col_models);$i++){

                    $column = $col_models[$i];

                    $f_data = trim($column["column"]["name"],"'");

                    if($column["type"]=="field"){

                        if(isset($u_rec[$column["field"]])){
                            $t_resp[$f_data] = $u_rec[$column["field"]];
                        }else if(!is_null($c_rec) && isset($c_rec[$column["field"]])){
                            $t_resp[$f_data] = $c_rec[$column["field"]];
                        }else{
                            $t_resp[$f_data] = "";
                        }

                    }else{

                        if(isset($column["delegate"]) && isset($u_rec[$column["delegate"]])){
                            $t_resp[$f_data] = $u_rec[$column["delegate"]];
                        }else if(!is_null($c_rec) && isset($column["delegate"]) && isset($c_rec[$column["delegate"]])){
                            $t_resp[$f_data] = $c_rec[$column["delegate"]];
                        }else{
                            $t_resp[$f_data] = "{$f_data}";
                        }

                    }
                }

                if(!empty($t_resp)){ $response_datas[] = $t_resp; }

            }

        }

        return $response->withJson($response_datas);

    }

}