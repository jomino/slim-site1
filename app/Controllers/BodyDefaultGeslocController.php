<?php

namespace App\Controllers;

use Framework\ArrayMethods as ArrayMethods;

class BodyDefaultGeslocController extends \Core\Controller
{
    private $views_path = "Default/App/Content";

    public function home($request, $response, $args)
    {
        $client = $this->client->model;
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
            ),
            "action_gesloc_edit" => array(
                "action" => array(
                    "gesloc-action-edit",
                    $router->pathFor('gesloc_edit')
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
                "value" => "1",
                "text" => "default.rentok"
            ),
            array(
                "name" => $grp_name,
                "value" => "2",
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

        $view_path = $this->views_path."/Gesloc/footable-bs.html.twig";

        return $this->view->render( $response, $view_path, $script_datas);
        
    }

    public function pipe($request, $response, $args)
    {

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
                                $t_blt = $this->_getBelongTo($column["field"],$column["delegate"],$u_rec);
                                $t_resp[$f_data] = $t_blt;
                                //$this->logger->debug("Record BelongTo [".$column["field"]."]:",["result"=>$t_blt]);
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

    public function edit($request, $response, $args)
    {
        $client = $this->client->model;

        $_id = null;

        if(!empty($args["id"])){
            $_id = $args["id"];
            $ingoing = \App\Models\Gesloc::first(array(
                "idgesloc = ?" => $_id
            ));
        }

        $_items = array();

        // form
        $viewmodel = new \App\Models\Views\GeslocDefaultEditViewModel(array(
            "logger" => $this->logger,
            "model" => !empty($ingoing) ? $ingoing : \App\Models\Gesloc::class
        ));

        $_items = $viewmodel->getItems("form-edit");
        
        $form_items = array($_items);

        $form_id = $_items["id"];
        $form_title = $this->translator->trans($_items["title"]);

        $form_validate = array();
        
        if(isset($_items["validate"])){
            $form_validate = $_items["validate"];
        }

        $form_hiddens = array( array(
            "value" => $client->uri,
            "name" => "agence"
        ));

        if(!is_null($_id)){
            $form_hiddens[] = array(
                "value" => $_id,
                "name" => "idgesloc"
            );
        }

        $path_id = !is_null($_id) ? array("id" => $_id):[];

        $form_action = $this->router->pathFor('properties_save',$path_id);

        $form_datas = array(
            "form_id" => $form_id,
            "form_action" => $form_action,
            "form_items" => $form_items,
            "form_validate" => $form_validate,
            "form_hiddens" => $form_hiddens
        );
        
        $form = $this->view->fetch("Default/App/Renderer/form.html.twig",$form_datas);

        $box_datas = array(
            "items" => array(
                array(
                    "id" => "box-gesloc-edit",
                    "title" => "messages.title_gesloc_edit",
                    "back" => 1,
                    "body" => $form
                )
            )
        );

        $view_path = $this->views_path."/Gesloc/edit-bs.html.twig";

        return $this->view->render( $response, $view_path, $box_datas);
        
    }

    public function save($request, $response, $args)
    {
        return;
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
            //$this->logger->debug("Record BelongTo :",["record"=>(array)$t_rec->getRaw()]);
            for($i=0;$i<sizeof($t_dest);$i++){
                $t_base = $base_path.$t_dest[$i];
                $t_blt = $t_rec->getBelongTo($t_base);
                $a_ret[] = $t_blt;
                //$this->logger->debug("Result BelongTo [".$t_base."]:",["result"=>$t_blt]);
            }
        }else{
            $this->logger->debug("Phantom record :",[$orig]);
        }
        return trim(implode(" ",$a_ret));
    }

}