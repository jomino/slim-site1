<?php

namespace App\Controllers;

use Framework\ArrayMethods as ArrayMethods;

class BodyDefaultPropertiesController extends \Core\Controller
{
    private $views_path = "Default/App/Content";

    public function home($request, $response, $args)
    {
        $client = $this->client->model;
        $router = $this->router;
        $assets = $this->assets;
        $translator = $this->translator;

        $table_id = "dt-properties";

        $script_datas = array();

        // global
        $script_datas["table_id"] = $table_id;
        $script_datas["table_hdl"] = $router->pathFor('properties_pipe');

        $model = new \App\Models\Views\PropertiesDefaultListFootable(array(
            "data" => array(
                "action_properties_edit" => array(
                    "action" => array(
                        "properties-edit",
                        $this->router->pathFor('properties_edit')
                    )
                )
            )
        ));

        $script_datas["table_defs"] = $model->getColumns();

        $script_datas["table_scripts"] = array_merge(
            $assets->getPaths("footable_lib","css","vendor"),
            $assets->getPaths("footable_lib","js","vendor")
        );

        $grp_name = "ptype";
        $fld_name = "id_ptype";

        $table_util_radios = array(
            array(
                "name" => $grp_name,
                "value" => "0",
                "text" => "default.all",
                "checked" => 1
            ),
            array(
                "name" => $grp_name,
                "value" => \App\Statics\Models::PROPERTY_TYPE_APPART,
                "text" => "default.appart"
            ),
            array(
                "name" => $grp_name,
                "value" => \App\Statics\Models::PROPERTY_TYPE_HOUSE,
                "text" => "default.house"
            )
        );

        $script_datas["table_util_top"] = array(
            "title" => $translator->trans("messages.prop_util_title"),
            "radios" => $table_util_radios,
            "script" => "$('input[name={$grp_name}]').on('ifChecked', function(){
                    if(FooTable.get('#{$table_id}')){ 
                        var filtering = FooTable.get('#{$table_id}').use(FooTable.Filtering),
                            filter = $(this).val();
                        if(this.checked){
                            if(filter === '0'){ filtering.removeFilter('{$grp_name}');}
                            else{ filtering.addFilter('{$grp_name}', filter, ['{$fld_name}']); }
                            filtering.filter();
                        }
                    }
                }
            );"
        );

        $view_path = $this->views_path."/Properties/footable-bs.html.twig";

        /*print("<pre>");
        print_r($script_datas);
        print("</pre>");*/

        return $this->view->render( $response, $view_path, $script_datas);
        
    }

    public function pipe($request, $response, $args)
    {

        $params = json_decode($request->getBody(),false);

        $client = $this->client->model;

        $where = array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_PROPERTY
        );

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            $query->where($k,$v);
        }

        $query->join("properties","properties.id_prop=ingoing.id_ref",["properties.*"]);

        //$query->limit(5);

        $query->order("properties.name","ASC");

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
                    $v_rec = array();
                    if(!empty($params->search)){
                        $s_search = $params->search;
                        if( strpos($u_raw->name,$s_search)!==false || 
                            strpos($u_raw->street,$s_search)!==false || 
                            strpos($u_raw->cp,$s_search)!==false || 
                            strpos($u_raw->ville,$s_search)!==false )
                        {
                            $v_rec["property"] = $u_raw;
                        }
                    }else{
                        $v_rec["property"] = $u_raw;
                    }
                    if(!empty($v_rec)){ $valid_recs[] = $v_rec; }
                }
            }

            $filtered_records = sizeof($valid_recs);

            //var_dump($valid_recs);

            if(!empty($valid_recs)){

                $model = new \App\Models\Views\PropertiesDefaultListFootable();
                $col_models = $model->getMap("properties");

                for($j=0;$j<sizeof($valid_recs);$j++){
                    $t_resp = array();
                    $u_rec = $valid_recs[$j]["property"];
                    for($i=0;$i<sizeof($col_models);$i++){
                        $column = $col_models[$i];
                        $f_data = trim($column["column"]["name"],"'");
                        $c_field = isset($column["field"]) ? $column["field"]:"";
                        if($column["type"]=="field"){
                            if(isset($u_rec[$c_field])){
                                $t_resp[$f_data] = $u_rec[$c_field];
                            }else{
                                $t_resp[$f_data] = "";
                            }
                        }else if($column["type"]=="list"){
                            if(!empty($c_field)){ $t_resp[$f_data] = $this->_getList($column["list"],$c_field); }
                        }else{
                            if(isset($column["delegate"])){
                                if(isset($u_rec[$column["delegate"]])){
                                    $t_resp[$f_data] = $u_rec[$column["delegate"]];
                                }else{
                                    $t_resp[$f_data] = "";
                                }
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

    protected function _getList($className,$classProperty)
    { // !important: sortir l'opération du flux
        $t_resp = array();
        $_res = $className::all();
        if(!empty($_res)){
            for($j=0;$j<sizeof($_res);$j++){
                $t_resp[] = array(
                    "name" => ucfirst($_res[$j]->getDisplay()->{$classProperty}),
                    "value" => $_res[$j]->getId()
                );
            }
        }
        return($t_resp);
    }

    public function edit($request, $response, $args)
    {
        $client = $this->client->model;

        $_id = null;

        if(!empty($args["id"])){
            $_id = $args["id"];
            $ingoing = \App\Models\Properties::first(array(
                "id_prop = ?" => $_id
            ));
        }

        // form
        $viewmodel = new \App\Models\Views\PropertiesDefaultEditViewModel(array(
            "logger" => $this->logger,
            "model" => !empty($ingoing) ? $ingoing : \App\Models\Properties::class
        ));

        $_items = $viewmodel->getItems("form-edit");

        $form_id = $_items["id"];
        $form_title = $this->translator->trans($_items["title"]);

        $form_validate = array();
        
        if(isset($_items["validate"])){
            $form_validate = $_items["validate"];
        }
        
        $form_items = array($_items);

        $form_hidden_agence = array(
            "value" => $client->uri,
            "name" => "agence"
        );

        $path_id = !is_null($_id) ? array("id" => $_id):[];

        $form_action = $this->router->pathFor('properties_save',$path_id);

        $form_datas = array(
            "form_id" => $form_id,
            "form_action" => $form_action,
            "form_items" => $form_items,
            "form_validate" => $form_validate,
            "form_hiddens" => array(
                $form_hidden_agence
            )
        );
        
        $form = $this->view->fetch("Default/App/Renderer/form.html.twig",$form_datas);

        $box_datas = array(
            "items" => array(
                array(
                    "id" => "box-property-edit",
                    "title" => "messages.title_property_edit",
                    "back" => 1,
                    "body" => $form
                )
            )
        );

        $view_path = $this->views_path."/Properties/edit-bs.html.twig";

        return $this->view->render( $response, $view_path, $box_datas);
        
    }

    public function save($request, $response, $args)
    {
        return;
    }

}