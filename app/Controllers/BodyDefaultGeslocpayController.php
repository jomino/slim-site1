<?php

namespace App\Controllers;

use App\Models\Geslocpay as Geslocpay;

use Framework\ArrayMethods as ArrayMethods;
use Framework\DateMethods as DateMethods;

class BodyDefaultGeslocpayController extends \Core\Controller
{
    private $views_path = "Default/App/Content";

    public function home($request, $response, $args)
    {

        if(empty($args["id"])){
            return;
        }

        $_id = $args["id"];

        $router = $this->router;
        $translator = $this->translator;
        $view = $this->view;
        $client = $this->client->model;

        $table_id = "dt-geslocpay-".$_id;

        $script_datas = array();

        // global
        $script_datas["table_id"] = $table_id;
        $script_datas["table_hdl"] = $router->pathFor('geslocpay_pipe',["id"=>$_id]);

        // form
        $viewmodel = new \App\Models\Views\GeslocpayDefaultEditViewModel(array(
            "model" => Geslocpay::class
        ));

        $_items = $viewmodel->getItems("form-edit");

        $form_id = $_items["id"];
        $form_title = $translator->trans($_items["title"]);

        $form_validate = array();
        
        if(isset($_items["validate"])){
            $form_validate = $_items["validate"];
        }
        
        $form_items = array($_items);

        // only for modal popup use
        /*$form_hidden_title = array(
            "value" => $form_title,
            "classes" => array("form-title")
        );*/

        $form_hidden_agence = array(
            "value" => $client->uri,
            "name" => "agence"
        );

        $form_hidden_idgesloc = array(
            "value" => $_id,
            "name" => "idgesloc"
        );

        $form_hidden_idpay = array(
            "value" => "0",
            "name" => "idpay"
        );

        $form_action = $router->pathFor('geslocpay_save');

        $form_datas = array(
            "form_id" => $form_id,
            "form_action" => $form_action,
            "form_items" => $form_items,
            "form_validate" => $form_validate,
            "form_hiddens" => array(
                $form_hidden_idgesloc,
                $form_hidden_idpay,
                $form_hidden_agence
            )
        );
        
        $table_util_body = $view->fetch("Default/App/Renderer/form.html.twig",$form_datas);

        $table_vmodel = new \App\Models\Views\GeslocpayListFootable(array(
            "data" => array(
                "action_edit_pay" => array(
                    "action" => array( "gesloc-edit-pay", $form_id )
                ),
                "action_delete_pay" => array(
                    "action" => array( "gesloc-delete-pay", $router->pathFor('geslocpay_del'))
                )
            )
        ));

        $script_datas["table_defs"] = $table_vmodel->getColumns();

        $script_datas["table_scripts"] = array_merge(
            $this->assets->getPaths("footable_lib","css","vendor"),
            $this->assets->getPaths("footable_lib","js","vendor")
        );

        $script_datas["table_util_top"] = array(
            "id" => "geslocpay-form-box",
            "expandable" => 1,
            "collapsed" => 1,
            "title" => $translator->trans("messages.geslocpay_edit_title"),
            "body" => $table_util_body
        );

        /*print("<pre>");
        print_r($script_datas);
        print("</pre>");*/

        return $this->view->render( $response, "{$this->views_path}/Geslocpay/footable-bs.html.twig", $script_datas);


    }

    public function pipe($request, $response, $args)
    {

        if(empty($args["id"])){
            return;
        }

        $_id = $args["id"];

        $where = array(
            "idgesloc = ?" => $_id
        );

        $ingoings = Geslocpay::all($where,array("*"),"dt_debit","DESC");

        $response_datas = array();

        $total_records = 0;
        $filtered_records = 0;

        $valid_recs = array();

        if(!empty($ingoings)){

            $total_records = sizeof($ingoings);

            for($i=0;$i<$total_records;$i++){
                $u_raw = $ingoings[$i];
                if(!empty($u_raw)){
                    $valid_recs[] = array(
                        "recs" => $u_raw
                    );
                }
            }

            $filtered_records = sizeof($valid_recs);

            //var_dump($valid_recs);

            if(!empty($valid_recs)){

                $viewmodel = new \App\Models\Views\GeslocpayListFootable();
                $col_models = $viewmodel->getMap("geslocpay");

                for($j=0;$j<sizeof($valid_recs);$j++){
                    $t_resp = array();
                    $_rec = $valid_recs[$j]["recs"];
                    $u_rec = (array) $_rec->getRaw();
                    for($i=0;$i<sizeof($col_models);$i++){
                        $column = $col_models[$i];
                        $c_field = isset($column["field"]) ? $column["field"]:"";
                        $f_data = trim($column["column"]["name"],"'");
                        if($column["type"]=="field"){
                            if(strpos($c_field,".")!==false){
                                $t_resp[$f_data] = $_rec->getBelongTo($c_field);
                            }else if(isset($u_rec[$c_field])){
                                $t_resp[$f_data] = $u_rec[$c_field];
                            }else{
                                $t_resp[$f_data] = "";
                            }
                        }else if($column["type"]=="list"){
                            if(!empty($c_field)){ $t_resp[$f_data] = $this->_getList($column["list"],$c_field); }
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

        if(empty($args["id"])){
            return;
        }

        $_id = $args["id"];

        $where = array(
            "idpay = ?" => $_id
        );

        $ingoing = Geslocpay::first($where);

        $_items = array();

        if(!empty($ingoing)){

            $viewmodel = new \App\Models\Views\GeslocpayDefaultEditViewModel(array(
                "model" => $ingoing
            ));

            $_items = $viewmodel->getItems("form-edit");

            $form_id = $_items["id"];
            $form_title = $_items["title"];

        }

        /*print("<pre>");
        print_r($_items);
        print("</pre>");*/
        
        $form_items = array($_items);

        $form_action = $this->router->pathFor('geslocpay_save')."/{$_id}";

        $form = array(
            "form_id" => $form_id,
            "form_title" => $form_title,
            "form_action" => $form_action,
            "form_items" => $form_items
        );

        return $this->view->render( $response, "Default/App/Renderer/form.html.twig", $form);

    }

    public function save($request, $response, $args)
    {
        $translator = $this->translator;

        $success = false;
        $message = "done";

        $params = (array) $request->getParsedBody();

        $c_name = Geslocpay::getPrimaryKeys()[0]["name"];

        $primary = $params[$c_name];
        unset($params[$primary]);

        if(empty($params["dt_credit"])){
            if(empty((float) $params["creditsum"])){
                $params["dt_credit"] = null;
            }else{
                $params["dt_credit"] = DateMethods::now();
            }
        }

        if(empty($primary)){

            $ingoing = new Geslocpay(array(
                "data" => $params
            ));

            if($ingoing->validate()){

                $insertId = $ingoing->insert();

                if(empty($insertId)){
                    $message = "error_insert";
                }else{
                    $success = true;
                    $message = "success_insert::{$insertId}";
                }

            }else{
                $message = $ingoing->errors;
            }

        }else{

            $where = array( "idpay = ?" => $primary );

            $ingoing = Geslocpay::first($where);

            if(!empty($ingoing)){

                $assert = array_merge(array(
                    "debitsum = ?" => $params["debitsum"],
                    "paytype = ?" => $params["paytype"]
                ), $where);

                if($ingoing->assert($assert)){

                    unset($params["debitsum"]);
                    unset($params["paytype"]);

                    $ingoing->set($params);

                    if($ingoing->isDirty()===true){

                        if($ingoing->validate()){

                            if($ingoing->update()){
                                $success = true;
                                $message = $translator->trans("messages.success_update");
                            }else{
                                $message = array(
                                    "error" => [$translator->trans("messages.unknow_error_occur")]
                                );
                            }

                        }else{
                            $message = $ingoing->errors;
                        }

                    }else{
                        $message = array(
                            "error" => [$translator->trans("messages.form_nochange_performed")]
                        );
                    }

                }else{
                    $message = array(
                        "error" => [$translator->trans("messages.form_nochange_allowed")]
                    );
                }

            }else{
                $message = array(
                    "error" => [$translator->trans("messages.no_record_for")."_{$primary}"]
                );
            }

        }

        $response_datas = array(
            "success" => $success,
            "message" => $message
        );

        return $response->withJson($response_datas);

    }

    public function del($request, $response, $args)
    {
        
        $translator = $this->translator;

        if(empty($args["id"])){
            return;
        }

        $success = false;
        $message = "done";

        $_id = $args["id"];

        $primary = Geslocpay::getPrimaryKeys()[0]["name"];

        $where = array(
            "{$primary} = ?" => $_id
        );

        $ingoing = Geslocpay::first($where);

        if(!empty($ingoing)){

            if($ingoing->delete()){
                $success = true;
                $message = $translator->trans("messages.success_delete");
            }else{
                $message = array(
                    "error" => [$translator->trans("messages.unknow_error_occur")]
                );
            }

        }else{
            $message = array(
                "error" => [$translator->trans("messages.no_record_for")."_{$primary}"]
            );
        }

        $response_datas = array(
            "success" => $success,
            "message" => $message
        );

        return $response->withJson($response_datas);

    }

}