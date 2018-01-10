<?php

namespace App\Controllers;

use App\Auth\Auth as Auth;

use Framework\ArrayMethods as ArrayMethods;

class BodyDefaultGeslocController extends \Core\Controller
{
    private $views_path = "Default/App/Content";

    public function home($request, $response, $args)
    {
        $router = $this->container->get('router');

        $table_id = "dt-gesloc";

        $script_datas = array();

        $_data = array(
            "action_pay" => array(
                "action" => array(
                    "gesloc-action-pay",
                    $router->pathFor('geslocpay_view')
                )
            )
        );

        // global
        $script_datas["table_id"] = $table_id;
        $script_datas["table_hdl"] = $router->pathFor('gesloc_pipe');

        $model = new \App\Models\Views\GeslocListFootable(array(
            "data" => $_data
        ));

        $script_datas["table_defs"] = $model->getColumns();

        $script_datas["table_scripts"] = array_merge(
            $this->assets->getPaths("footable_lib","css","vendor"),
            $this->assets->getPaths("footable_lib","js","vendor")
        );

        /*print("<pre>");
        print_r($script_datas);
        print("</pre>");*/

        return $this->view->render( $response, "{$this->views_path}/Gesloc/footable-bs.html.twig", $script_datas);
        
    }

    public function pipe($request, $response, $args)
    {

        $params = json_decode($request->getBody(),false);

        $client = $this->client->model;

        $where = array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT
        );

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            $query->where($k,$v);
        }

        $query->join("gesloc","gesloc.idgesloc=ingoing.id_ref",["gesloc.*"]);
        $query->join("properties","properties.id_ref=gesloc.idbien",["properties.*"]);

        $query->order("gesloc.ref","ASC");

        $ingoings = $query->all();

        $response_datas = array();

        $total_records = 0;
        $filtered_records = 0;

        $valid_recs = array();

        if(!empty($ingoings)){

            $total_records = sizeof($ingoings);

            for($i=0;$i<$total_records;$i++){
                $u_raw = $ingoings[$i];
                if(!empty($u_raw)){
                    unset($u_raw["id_cli"]);
                    $valid_recs[] = array(
                        "recs" => $u_raw
                    );
                }
            }

            $filtered_records = sizeof($valid_recs);

            //var_dump($valid_recs);

            if(!empty($valid_recs)){

                $model = new \App\Models\Views\GeslocListFootable();
                $col_models = $model->getMap("gesloc");

                for($j=0;$j<sizeof($valid_recs);$j++){
                    $t_resp = array();
                    $u_rec = $valid_recs[$j]["recs"];
                    for($i=0;$i<sizeof($col_models);$i++){
                        $column = $col_models[$i];
                        $f_data = trim($column["column"]["name"],"'");
                        if($column["type"]=="field"){
                            if(isset($u_rec[$column["field"]])){
                                $t_resp[$f_data] = $u_rec[$column["field"]];
                            }else{
                                $t_resp[$f_data] = "";
                            }
                        }else{
                            if(isset($column["delegate"]) && isset($u_rec[$column["delegate"]])){
                                $t_resp[$f_data] = $u_rec[$column["delegate"]];
                            }else{
                                $t_resp[$f_data] = "{$f_data}";
                            }
                        }
                    }
                    if(!empty($t_resp)){ $response_datas[] = $t_resp; }
                }

            }

        }

        return $response->withJson($response_datas);

    }

}