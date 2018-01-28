<?php

namespace App\Controllers;

use Framework\ArrayMethods as ArrayMethods;

class BodyDefaultUsersController extends \Core\Controller
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
        $script_datas["table_hdl"] = $router->pathFor('contact_pipe');

        $model = new \App\Models\Views\UsersListFootable(array(
            "data" => array(
                "action_contact_edit" => array(
                    "action" => array(
                        "contact-edit",
                        $this->router->pathFor('contact_edit')
                    )
                )
            )
        ));

        $script_datas["table_defs"] = $model->getColumns();

        $script_datas["table_scripts"] = array_merge(
            $assets->getPaths("footable_lib","css","vendor"),
            $assets->getPaths("footable_lib","js","vendor")
        );

        $grp_name = "utype";
        $fld_name = "id_utype";

        $table_util_radios = array(
            array(
                "name" => $grp_name,
                "value" => "0",
                "text" => "default.all",
                "checked" => 1
            ),
            array(
                "name" => $grp_name,
                "value" => \App\Statics\Models::USER_TYPE_TENANT,
                "text" => "default.tenant"
            ),
            array(
                "name" => $grp_name,
                "value" => \App\Statics\Models::USER_TYPE_OWNER,
                "text" => "default.owner"
            ),
            array(
                "name" => $grp_name,
                "value" => \App\Statics\Models::USER_TYPE_SYNDIC,
                "text" => "default.syndic"
            )
        );

        $script_datas["table_util_top"] = array(
            "title" => $translator->trans("messages.user_util_title"),
            "radios" => $table_util_radios,
            "script" => "$('input[name={$grp_name}]').on('ifChecked', function(){
                    if(FooTable && FooTable.get('#{$table_id}')){ 
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

        /*print("<pre>");
        print_r($script_datas);
        print("</pre>");*/

        return $this->view->render( $response, "{$this->views_path}/Contacts/footable-bs.html.twig", $script_datas);
        
    }

    public function pipe($request, $response, $args)
    {

        $params = json_decode($request->getBody(),false);

        $client = $this->client->model;

        $where = array(
            "id_cli = ?" => $client->getRaw()->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_USERS
        );

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            $query->where($k,$v);
        }

        $query->join("users","users.id_user=ingoing.id_ref",["users.*"]);

        //$query->limit(5);

        $query->order("users.nom","ASC");

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
                        if( strpos($u_raw["nom"],$s_search)!==false || 
                            strpos($u_raw["pnom"],$s_search)!==false || 
                            strpos($u_raw["street"],$s_search)!==false || 
                            strpos($u_raw["cp"],$s_search)!==false || 
                            strpos($u_raw["ville"],$s_search)!==false)
                        {
                            $v_rec["user"] = $u_raw;
                        }
                        if(!in_array($id_user,$valid_recs)){
                            // todo chercher email/tel dans table contacts
                            $v_rec["contacts"] = array();
                        }
                    }else{
                        $t_contact = array();
                        $contact = \App\Models\Contacts::first( array(
                            "id_user = ?" => $u_raw["id_user"],
                            "id_ctype = ?" => \App\Statics\Models::CONTACT_TYPE_PHONE
                        ));
                        if(!empty($contact)){
                            $contact_type = $contact->getBelongTo("id_ctype.ref_ctype");
                            $t_contact[$contact_type] = $contact->contact;
                        }
                        $contact = \App\Models\Contacts::first( array(
                            "id_user = ?" => $u_raw["id_user"],
                            "id_ctype = ?" => \App\Statics\Models::CONTACT_TYPE_EMAIL
                        ));
                        if(!empty($contact)){
                            $contact_type = $contact->getBelongTo("id_ctype.ref_ctype");
                            $t_contact[$contact_type] = $contact->contact;
                        }
                        if(!empty($t_contact)){ $v_rec["contacts"] = $t_contact; }
                        $v_rec["user"] = $u_raw;
                    }
                    if(!empty($v_rec)){ $valid_recs[] = $v_rec; }
                }
            }

            $filtered_records = sizeof($valid_recs);

            //var_dump($valid_recs);

            if(!empty($valid_recs)){

                $model = new \App\Models\Views\UsersListFootable();
                $col_models = $model->getMap("contacts");

                for($j=0;$j<sizeof($valid_recs);$j++){
                    $t_resp = array();
                    $u_rec = $valid_recs[$j]["user"];
                    $c_rec = isset($valid_recs[$j]["contacts"]) ? $valid_recs[$j]["contacts"]:null;
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

        }

        //$table_response = new \App\Models\Interfaces\FootablePipelineResponse($response_datas);

        //$table_response->recordsTotal = $total_records;
        //$table_response->recordsFiltered = $filtered_records;

        return $response->withJson($response_datas);

    }

    public function edit($request, $response, $args)
    {
        $client = $this->client->model;

        $_id = null;

        if(!empty($args["id"])){
            $_id = $args["id"];
            $ingoing = \App\Models\Users::first(array(
                "id_user = ?" => $_id
            ));
        }

        $_items = array();

        // form
        $viewmodel = new \App\Models\Views\UsersDefaultEditViewModel(array(
            "logger" => $this->logger,
            "model" => !empty($ingoing) ? $ingoing : \App\Models\Users::class
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

        $form_action = $this->router->pathFor('contact_save',$path_id);

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
                    "id" => "box-contact-edit",
                    "title" => "messages.title_contact_edit",
                    "back" => 1,
                    "body" => $form
                )
            )
        );

        return $this->view->render( $response, "{$this->views_path}/Contacts/edit-bs.html.twig", $box_datas);
        
    }

    public function save($request, $response, $args)
    {

    }

}