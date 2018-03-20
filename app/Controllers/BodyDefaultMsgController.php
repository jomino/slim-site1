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
        $view = $this->view;

        $table_id = uniqid("dt-messages-");

        $script_datas = array();

        // global    
        $script_datas["table_id"] = $table_id;
        $script_datas["table_hdl"] = $router->pathFor('mailbox_pipe');

        $mailbox_edit_path = $router->pathFor("mailbox_edit");

        $model = new \App\Models\Views\MessagesDefaultListFootable(array(
            "data" => array(
                "action_message_read" => array(
                    "action" => array(
                        "action-message-read",
                        $mailbox_edit_path
                    )
                ),
                "action_message_name" => array(
                    "action" => array(
                        "action-message-name",
                        $mailbox_edit_path
                    )
                ),
                "action_message_title" => array(
                    "action" => array(
                        "action-message-title",
                        $mailbox_edit_path
                    )
                )
            )
        ));

        $script_datas["table_defs"] = $model->getColumns();

        $script_datas["table_scripts"] = array_merge(
            $assets->getPaths("footable_lib","css","vendor"),
            $assets->getPaths("footable_lib","js","vendor")
        );

        $table_util_id = uniqid("table-util-top-");

        $button_del_id = uniqid("button-del-checked-");
        $btn_del_icon = "fa-times";

        $btn_del_path = $router->pathFor("mailbox_del");

        $btn_del_msg_ok = $translator->trans("messages.del_msg_ok");
        $btn_del_msg_fail = $translator->trans("messages.del_msg_fail");

        $button_del_script = array( "script_run" => "
            var _href = '{$btn_del_path}',
                _callback = function(response){
                    \$.jo.refreshBody(()=>{
                        \$.jo.flash(
                            (response && response.success) ? 'ok':'error',
                            (response && response.success) ? '{$btn_del_msg_ok}':'{$btn_del_msg_fail}',
                            (response && response.success) ? response.message:(response.error ? response.error:'unknow error')
                        );
                    });
                };
            $('button#{$button_del_id}').off('click').on('click', function(){
                var _checked = \$.jo.msgsFootableUtil || [];
                if(_checked.length){
                    \$.jo.loadDatas(_href,null,window.btoa(_checked.join(',')),_callback);
                }
            });
        ");

        $button_del_checked = array(
            "id" => $button_del_id,
            "tpl" => "form-button",
            "layout" => STATICS::BS_LAYOUT_6COL,
            "icon" => array("fa",$btn_del_icon,"valign-middel","text-red"),
            "classes" => array("btn-default","text-red","flat","disabled","valign-middel"),
            "style" => array("margin-top: 5px;"),
            "label" => "default.table_del_checked",
            "script" => $view->fetch( "Scripts/jqready.html.twig", $button_del_script )
        );

        $button_check_id = uniqid("button-check-all-");
        $btn_checked_icon = "fa-check-square-o";
        $btn_unchecked_icon = "fa-square-o";

        $button_check_script = array( "script_run" => "
            $('button#{$button_check_id}').off('click').on('click', function(){
                var \$icon = \$('span.fa',\$(this));
                var \$_cb = \$('input:checkbox:visible',\$('#{$table_id}'));
                if(\$icon.length){
                    if(\$icon.hasClass('{$btn_unchecked_icon}')){
                        \$_cb.iCheck('check');
                        \$icon.removeClass('{$btn_unchecked_icon}');
                        \$icon.addClass('{$btn_checked_icon}');
                    }else{
                        \$_cb.iCheck('uncheck');
                        \$icon.addClass('{$btn_unchecked_icon}');
                        \$icon.removeClass('{$btn_checked_icon}');
                    }
                }
            });
        ");

        $button_check_all = array(
            "id" => $button_check_id,
            "tpl" => "form-button",
            "layout" => STATICS::BS_LAYOUT_6COL,
            "icon" => array("fa",$btn_unchecked_icon,"valign-middel"),
            "classes" => array("btn-primary","flat","valign-middel"),
            "style" => array("margin-top: 5px;"),
            "label" => "default.table_check_all",
            "script" => $view->fetch( "Scripts/jqready.html.twig", $button_check_script )
        );

        $button_radios_sent_id = uniqid("button-radios-sent-");

        $button_radios_grp_name = uniqid("msg");

        $button_radios_fld_name = "sent_or_received";

        $button_radios_list = array(
            array(
                "name" => $button_radios_grp_name,
                "value" => "-1",
                "text" => "default.all",
                "checked" => 1
            ),
            array(
                "name" => $button_radios_grp_name,
                "value" => "0",
                "text" => "messages.mailbox_msg_received"
            ),
            array(
                "name" => $button_radios_grp_name,
                "value" => "1",
                "text" => "messages.mailbox_msg_sent"
            )
        );

        $button_radios_script = "
            // \$.jo.debugStop(); 
            var _filter = function(filter){ 
                    var filtering = FooTable.get('#{$table_id}').use(FooTable.Filtering);
                    if(filter === '-1'){ filtering.removeFilter('{$button_radios_grp_name}');}
                    else{ filtering.addFilter('{$button_radios_grp_name}', filter, ['{$button_radios_fld_name}']); }
                    filtering.filter();
                };
            \$('input[name={$button_radios_grp_name}]').on('ifChecked', function(){
                if(FooTable && FooTable.get('#{$table_id}')){
                    if(this.checked){ _filter($(this).val()); }
                }
            });
        ";

        $button_radios_sent = array(
            "tpl" => "form-radio",
            "layout" => STATICS::BS_LAYOUT_4COL,
            "id" => $button_radios_sent_id,
            "name" => $button_radios_sent_id,
            "style" => array("min-width: 350px;"),
            "list" => $button_radios_list,
            "script" => $button_radios_script
        );

        $button_new_id = uniqid("button-add-new-");
        $btn_new_icon = "fa-envelope-square";

        $mailbox_new_href = $router->pathFor("mailbox_edit");

        $button_new_script = array( "script_run" => "
            // \$.jo.debugStop();
            $('button#{$button_new_id}').off('click').on('click', function(){
                $.jo.loadHtml('{$mailbox_href_href}');
            });
        ");

        $button_add_new = array(
            "id" => $button_new_id,
            "tpl" => "form-button",
            "layout" => STATICS::BS_LAYOUT_6COL." pull-right",
            "icon" => array("fa",$btn_new_icon,"valign-middel"),
            "classes" => array("btn-primary","flat","valign-middel","pull-right"),
            "style" => array("margin-top: 5px;"),
            "label" => "messages.mailbox_add_new",
            "script" => $view->fetch( "Scripts/jqready.html.twig", $button_new_script )
        );

        $input_search_id = uniqid("mailbox-input-search-");
        $input_icon_after = '<span class="glyphicon glyphicon-search"></span>';

        $input_search_script = "
            // \$.jo.debugStop();
        ";

        $top_input_search = array(
            "id" => $input_search_id,
            "name" => $input_search_id,
            "tpl" => "form-input",
            "type" => "text",
            "style" => array("margin-top: 5px;","margin-right: -10px;"),
            "icon_after" => $input_icon_after,
            "script" => $input_search_script,
            "classes" => array_merge(array("pull-right"),explode(" ",STATICS::BS_LAYOUT_4COL))
        );

        $table_util_top = array(
            "id" => $table_util_id,
            "tpl" => "form-box",
            "title" => "messages.mailbox_utils_title",
            "css" => array("margin-bottom: 5px;"),
            "pils" => array(
                "icon" => "envelope-square",
                "styles" => array( "width: 32px;", "height: 32px;"),
                "classes" => array( "bg-blue", "bd-blue")
            ),
            "items" => array(
                $button_check_all,
                $button_del_checked,
                $button_radios_sent,
                $button_add_new
            ),
            "header" => array(
                "components" => array($top_input_search)
            )
        );

        $script_datas["table_util_top"] = array( "items" => [$table_util_top] );

        $script_datas["table_util_bot"] = $view->fetch( "Scripts/jqready.html.twig", array( "script_run" => "
            var _cB = 0,
                setCB = function(n){ _cB+=n; },
                isCB = function(){ return _cB>0; },
                setButtonChecked = function(checked,value){ 
                    if(checked){
                        if(value){ \$.jo.msgsFootableUtil.push(value); }
                        \$('span.fa',\$('button#{$button_check_id}')).removeClass('{$btn_unchecked_icon}');
                        if(!\$('span.fa',\$('button#{$button_check_id}')).hasClass('{$btn_checked_icon}')){
                            \$('span.fa',\$('button#{$button_check_id}')).addClass('{$btn_checked_icon}');
                        }
                    }else{
                        if(value && \$.jo.msgsFootableUtil[\$.jo.msgsFootableUtil.indexOf(value)]){ delete(\$.jo.msgsFootableUtil[\$.jo.msgsFootableUtil.indexOf(value)]); }
                        \$('span.fa',\$('button#{$button_check_id}')).removeClass('{$btn_checked_icon}');
                        if(!\$('span.fa',\$('button#{$button_check_id}')).hasClass('{$btn_unchecked_icon}')){
                            \$('span.fa',\$('button#{$button_check_id}')).addClass('{$btn_unchecked_icon}');
                        }
                    }
                }; 
            \$.jo.msgsFootableUtil = [];
            \$('#{$table_id}').on( 'postdraw.ft.table after.ft.paging', function(){
                window.setTimeout( function(){
                    \$('input:checkbox:visible',\$('#{$table_id}')).iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue'
                    }).off('ifToggled').on('ifToggled', function(e){
                        setCB(this.checked ? 1:-1);
                        if(isCB()){
                            $('button#{$button_del_id}').removeClass('disabled');
                        }else{
                            $('button#{$button_del_id}').addClass('disabled');
                        } 
                        setButtonChecked(this.checked,this.value);
                    });
                }, 250 );
                // \$.jo.debugStop(); 
                setButtonChecked(\$('input:checked:visible',\$('#{$table_id}')).length>0);
            });
        "));

        return $this->view->render( $response, "{$this->views_path}/Messages/footable-bs.html.twig", $script_datas);
        
    }

    public function pipe($request, $response, $args)
    {

        $viewmodel = new \App\Models\Views\MessagesDefaultListFootable();

        $client = $this->client->model;

        $where = array(
            "messages.id_cli = ? OR messages.id_user = ?" => array($client->id_cli,$client->id_user)
        );

        $model = new \App\Models\Messages();

        $query = $model->connector->query()
            ->from($model->table,["messages.*"]);

        foreach($where as $k=>$v){
            if(is_array($v)){
                array_unshift($v,$k);
                $query->where(...$v);
            }else{
                $query->where($k,$v);
            }
        }

        $query->join("users","users.id_user=messages.id_user",["users.nom","users.pnom"]);

        $query->order("messages.received","DESC");

        $valid_recs = $query->all();

        $response_datas = array();

        $total_records = $filtered_records = sizeof($valid_recs);

        if(!empty($valid_recs)){

            $col_models = $viewmodel->getMap();

            for($j=0;$j<sizeof($valid_recs);$j++){

                $t_resp = array();

                $u_rec = $valid_recs[$j];

                for($i=0;$i<sizeof($col_models);$i++){

                    $_resp = array();

                    $column = $col_models[$i];

                    $f_data = trim($column["column"]["name"],"'");
                    $f_type = trim($column["column"]["type"],"'");

                    if(isset($column["type"]) && $column["type"]=="field"){

                        if(isset($u_rec[$column["field"]])){
                            $_resp["value"] = $f_type=="number" ? (int) $u_rec[$column["field"]]:$u_rec[$column["field"]];
                        }else{
                            $_resp["value"] = "";
                        }

                        if(isset($column["options"])){
                            $_resp["options"] = $column["options"];
                        }else{
                            /* correction bug footable  */
                            $_resp["options"] = array("classes"=>"");
                        }

                    }
                    
                    if(isset($column["type"]) && $column["type"]=="method"){
                        $method = $column["method"];
                        $fn = "_".$method["fn"];
                        $params = isset($method["params"]) ? (is_array($method["params"]) ? $method["params"]:[$method["params"]]):null;
                        if(method_exists($this,$fn)){
                            $_resp = call_user_func( array($this,$fn), $params, $u_rec);
                        }
                    }
                    
                    $t_resp[$f_data] = empty($_resp) ? "":$_resp;

                }

                if(!empty($t_resp)){ $response_datas[] = $t_resp; }

            }

        }

        return $response->withJson($response_datas);

    }

    public function del($request, $response, $args)
    {

        $params = $request->getParsedBody();

        $tokens = explode(",",base64_decode($params["token"]));

        $client = $this->client->model;

        $response_datas = array(
            "success" => true,
            "message" => $tokens
        );

        return $response->withJson($response_datas);

    }

    public function edit($request, $response, $args)
    {
        return;
    }

    private function _attach($params,$record)
    {
        $fake_data = random_int(0,500)>250 ? 1:0;
        return array(
            "value" => $fake_data,
            "options" => array("classes" => "")
        );
    }

    private function _sentOrReceived($params,$record)
    {

        $value = "";
        $options = array("classes" => "");

        $client = $this->client->model;

        $value = $record["id_user"]==$client->id_user ? 0:1; // reçu:envoyé

        return array(
            "value" => $value,
            "options" => $options
        );

    }

}