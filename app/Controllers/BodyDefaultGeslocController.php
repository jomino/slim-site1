<?php

namespace App\Controllers;

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
            ),
            "gesloc_full_adress" => array(
                "action" => array(
                    "gesloc-full-adress",
                    $router->pathFor('properties_edit')
                )
            ),
            "gesloc_edit_tenant" => array(
                "action" => array(
                    "gesloc-edit-tenant",
                    $router->pathFor('contact_edit')
                )
            ),
            "gesloc_edit_owner" => array(
                "action" => array(
                    "gesloc-edit-owner",
                    $router->pathFor('contact_edit')
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

        $grp_name = "udebit";
        $fld_name = "endebit";

        $table_util_radios = array(
            array(
                "name" => $grp_name,
                "value" => "-1",
                "text" => "default.all",
                "checked" => 1
            ),
            array(
                "name" => $grp_name,
                "value" => "0",
                "text" => "default.rentok"
            ),
            array(
                "name" => $grp_name,
                "value" => "1",
                "text" => "default.rentdue"
            )
        );

        $script_datas["table_util_top"] = array(
            "title" => $this->translator->trans("messages.gesloc_util_title"),
            "radios" => $table_util_radios,
            "script" => "$('input[name={$grp_name}]').on('ifChecked', function(){
                    if(FooTable && FooTable.get('#{$table_id}')){ 
                        var filtering = FooTable.get('#{$table_id}').use(FooTable.Filtering),
                            filter = $(this).val();
                        if(this.checked){
                            if(filter === '-1'){ filtering.removeFilter('{$grp_name}');}
                            else{ filtering.addFilter('{$grp_name}', filter, ['{$fld_name}']); }
                            filtering.filter();
                        }
                    }
                }
            );"
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

            /*for($i=0;$i<$total_records;$i++){
                $u_raw = $ingoings[$i];
                if(!empty($u_raw)){
                    $valid_recs[] = array(
                        "recs" => $u_raw
                    );
                }
            }*/

            $valid_recs = $ingoings;

            $filtered_records = sizeof($valid_recs);

            //var_dump($valid_recs);

            if(!empty($valid_recs)){

                $model = new \App\Models\Views\GeslocListFootable();
                $col_models = $model->getMap("gesloc");

                for($j=0;$j<sizeof($valid_recs);$j++){
                    $t_resp = array();
                    $u_rec = $valid_recs[$j];
                    for($i=0;$i<sizeof($col_models);$i++){
                        $column = $col_models[$i];
                        $f_data = trim($column["column"]["name"],"'");
                        if($column["type"]=="field"){
                            if(strpos($column["field"],".")!==false){
                                $t_resp[$f_data] = $this->_getBelongTo($column["field"],$column["delegate"],$u_rec);
                            }else if(isset($u_rec[$column["field"]])){
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

    private function _getBelongTo($orig,$dest,$rec)
    {
        $a_ret = array();
        $t_orig = explode(".",$orig);
        $t_dest = is_array($dest) ? $dest:array($dest);
        $o_model = "\\App\\Models\\".ucfirst($t_orig[0]);
        $o_field = $t_orig[1];
        $p_col = $o_model::getPrimaryKeys()[0];
        $p_name = $p_col["name"];
        $t_rec = $o_model::first(array(
            "{$p_name} = ?" => $rec[$p_name]
        ));
        if(!empty($t_rec)){
            $base_path = $o_field.".";
            for($i=0;$i<sizeof($t_dest);$i++){
                $a_ret[] = $t_rec->getBelongTo($base_path.$t_dest[$i]);
            }
        }
        return trim(implode(" ",$a_ret));
    }

}