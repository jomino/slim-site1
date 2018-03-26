<?php

namespace App\Controllers;

use Framework\ArrayMethods as ArrayMethods;
use Framework\DateMethods as DateMethods;
use Framework\StringMethods as StringMethods;

use App\Statics\Models as STATICS;

class BodyDefaultMessagesController extends \Core\Controller
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

        $button_radios_local_name = "sentorreceived";

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
                },
                _savestate = function(value){
                    \$.jo.localStorage.set('{$button_radios_local_name}',parseInt(value));
                };
            \$('input[name={$button_radios_grp_name}]').on('ifChecked', function(){
                if(FooTable && FooTable.get('#{$table_id}')){
                    var \$val = \$(this).val();
                    if(this.checked){
                        _savestate(\$val);
                        _filter(\$val);
                    }
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
                $.jo.loadHtml('{$mailbox_new_href}');
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

        $input_search_id = uniqid("mailbox-search-");
        $input_icon_after = '<span class="glyphicon glyphicon-search"></span>';

        $input_search_script = "
            // \$.jo.debugStop(); 
            var _filter = function(filter){ 
                    var filtering = FooTable.get('#{$table_id}').use(FooTable.Filtering);
                    if(filter === ''){ filtering.removeFilter('{$input_search_id}');}
                    else{ filtering.addFilter('{$input_search_id}', filter, ['nom','pnom','title']); }
                    filtering.filter();
                };
            \$('input#{$input_search_id}').on('input', function(){
                if(FooTable && FooTable.get('#{$table_id}')){
                    var \$val = \$(this).val();
                    _filter(\$val);
                }
            });
        ";

        $top_input_search = array(
            "id" => $input_search_id,
            "name" => $input_search_id,
            "tpl" => "form-input",
            "type" => "text",
            "style" => array("margin-top: 5px;","margin-right: -10px;"),
            "icon_after" => $input_icon_after,
            "script" => $input_search_script,
            "classes" => array(STATICS::BS_LAYOUT_4COL,"pull-right")
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
                    var _span = \$('span.fa',\$('button#{$button_check_id}'));
                    if(checked){
                        if(value){ \$.jo.msgsFootableUtil.push(value); }
                        _span.removeClass('{$btn_unchecked_icon}');
                        if(!_span.hasClass('{$btn_checked_icon}')){
                            _span.addClass('{$btn_checked_icon}');
                        }
                    }else{
                        if(value && \$.jo.msgsFootableUtil[\$.jo.msgsFootableUtil.indexOf(value)]){ delete(\$.jo.msgsFootableUtil[\$.jo.msgsFootableUtil.indexOf(value)]); }
                        _span.removeClass('{$btn_checked_icon}');
                        if(!_span.hasClass('{$btn_unchecked_icon}')){
                            _span.addClass('{$btn_unchecked_icon}');
                        }
                    }
                }; 
            \$.jo.msgsFootableUtil = [];
            \$('#{$table_id}').on( 'postdraw.ft.table after.ft.paging', function(){
                window.setTimeout( function(){
                    var _btn = \$('button#{$button_del_id}');
                    \$('input:checkbox:visible',\$('#{$table_id}')).iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue'
                    }).off('ifToggled').on('ifToggled', function(e){
                        setCB(this.checked ? 1:-1);
                        if(isCB()){
                            _btn.removeClass('disabled');
                        }else{
                            _btn.addClass('disabled');
                        } 
                        setButtonChecked(this.checked,this.value);
                    });
                }, 250 ); 
                setButtonChecked(\$('input:checked:visible',\$('#{$table_id}')).length>0);
            });
            \$('#{$table_id}').on( 'postdraw.ft.table', function(){
                // \$.jo.debugStop();
                var _val = \$('input[name={$button_radios_grp_name}]:checked').first().val();
                var _state = \$.jo.localStorage.get('{$button_radios_local_name}');
                if(_state){
                    if(_state>-1 && _state!=_val){
                        window.setTimeout( function(){
                            \$('input[name={$button_radios_grp_name}]').eq(parseInt(_state)+1).iCheck('check');
                        }, 500 );
                    }
                }
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
                            $u_val = $f_type=="number" ? (int) $u_rec[$column["field"]]:$u_rec[$column["field"]];
                        }else{
                            $u_val = "";
                        }

                        $_resp["value"] = $u_val;

                        if(isset($column["options"])){
                            $u_opt = $column["options"];
                        }else{
                            $u_opt = array();
                        }

                        $u_opt["filterValue"] = $u_val;

                        $_resp["options"] = $u_opt;

                    }
                    
                    if(isset($column["type"]) && $column["type"]=="method"){
                        $method = $column["method"];
                        $fn = "_".$method["fn"];
                        $params = isset($method["params"]) ? (is_array($method["params"]) ? $method["params"]:[$method["params"]]):null;
                        if(method_exists($this,$fn)){
                            $_resp = call_user_func( array($this,$fn), $u_rec, $params);
                        }
                    }
                    
                    $t_resp[$f_data] = empty($_resp) ? "":$_resp;

                }

                if(!empty($t_resp)){ $response_datas[] = $t_resp; }

            }

        }

        return $response->withJson($response_datas);

    }

    public function send($request, $response, $args)
    {
        return $response->withJson(array(
            "success" => false,
            "message" => "not_yet_implemented"
        ));
    }

    public function del($request, $response, $args)
    {

        $params = $request->getParsedBody();

        $tokens = explode(",",base64_decode($params["token"]));

        $client = $this->client->model;

        $response_datas = array(
            "success" => false,
            "message" => "not_yet_implemented"
        );

        return $response->withJson($response_datas);

    }

    public function edit($request, $response, $args)
    {
        $form_tpl = "Default/App/Renderer/form.html.twig";

        $_id = null;

        if(!empty($args["id"])){
            $_id = $args["id"];
            $record = \App\Models\Messages::first(array(
                "id_msg = ?" => $_id
            ));
        }else{
            $record = new \App\Models\Messages();
        }

        $page_items = array();

        $message_content = $record->phantom===true ? "":$this->_messageContent($record);

        if( $this->_received($record) || $record->phantom===true ){

            if(!$record->proceed){
                $record->set("proceed",1);
                $record->update();
            }

            $form_datas = array(
                "mailbox_list_from" => $this->_contactsList($record),
                "mailbox_edit_title" => $this->_messageTitle($record),
                "mailbox_edit_content" => $this->_messageEdit($record)
            );

            $viewmodel = new \App\Models\Views\MessagesDefaultEditViewModel(array(
                "container" => $this->container,
                "datas" => $form_datas
            ));

            $_items = $viewmodel->getItems();

            $form_id = "mailbox-form-edit";
            //$form_title = $this->translator->trans($_items["title"]);

            $form_validate = array();
            
            if(isset($_items["validate"])){
                $form_validate = $_items["validate"];
            }
            
            $form_items = array($_items);

            /* $form_hidden_agence = array(
                "value" => $client->uri,
                "name" => "agence"
            ); */

            $path_id = !is_null($_id) ? array("id" => $_id):[];

            $form_action = $this->router->pathFor('mailbox_send',$path_id);

            $form_datas = array(
                "form_id" => $form_id,
                "form_action" => $form_action,
                "form_method" => "post",
                "form_items" => $form_items,
                "form_validate" => $form_validate/* ,
                "form_hiddens" => array(
                    $form_hidden_agence
                ) */
            );
            
            $page_items[] = array(
                "back" => 1,
                "expandable" => $record->phantom===true ? 0:1,
                "collapsed" => $record->phantom===true ? 0:1,
                "id" => "mailbox-msg-edit",
                "title" => $record->phantom===true ? "messages.mailbox_new_title":StringMethods::ellipsis($record->title,75),
                "body" => $this->view->fetch( $form_tpl, $form_datas)
            );

            if( $record->phantom===false ){
            
                $page_items[] = array(
                    "id" => "mailbox-msg-content",
                    "expandable" => 1,
                    "classes" => array("mailbox-msg-view"),
                    "title" => "messages.title_mailbox_content",
                    "body" => $message_content,
                    "pils" => array(
                        "icon" => "envelope-square",
                        "styles" => array( "width: 32px;", "height: 32px;"),
                        "classes" => array( "bg-blue", "bd-blue")
                    )
                );

            }

        }else{
        
            $page_items[] = array(
                "id" => "mailbox-msg-content",
                "back" => 1,
                "classes" => array("mailbox-msg-view"),
                "title" => StringMethods::ellipsis($record->title,75),
                "body" => $message_content
            );
            
        }

        $page_datas = array(
            "items" => $page_items
        );

        $view_path = $this->views_path."/Messages/edit-bs.html.twig";

        //$this->logger->debug( self::class, $viewmodel);

        return $this->view->render( $response, $view_path, $page_datas );

    }

    private function _contactsList($record=null)
    {

        $client = $this->client->model;

        $where = array(
            "ingoing.id_cli = ?" => $client->id_cli,
            "ingoing.id_cat = ?" => STATICS::CATEGORY_TYPE_USERS,
            "users.id_utype = ? OR users.id_utype = ?" => array(STATICS::USER_TYPE_TENANT,STATICS::USER_TYPE_OWNER)
        );

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            if(is_array($v)){
                array_unshift($v,$k);
                $query->where(...$v);
            }else{
                $query->where($k,$v);
            }
        }

        $query->join("users","users.id_user=ingoing.id_ref",["users.id_user","users.nom","users.pnom"]);

        $query->order("users.nom","ASC");

        $ingoings = $query->all();

        $data_list = array();

        if(!empty($ingoings)){
            for($i=0;$i<sizeof($ingoings);$i++){
                $ingoing = $ingoings[$i];
                $data_list[] = array(
                    "name" => trim($ingoing["nom"]." ".$ingoing["pnom"]),
                    "value" => $ingoing["id_user"]
                );
            }
        }

        $data_value = "";
        $data_raw = "-1";
        $data_readonly = $record->phantom===true ? 0:1;

        if($record->phantom===false){
            $data_raw = $record->id_user;
            $data_value = $record->getBelongTo("id_user.nom")." ".$record->getBelongTo("id_user.pnom");
        }

        return array(
            "id" => "mailbox-lst-name",
            "name" => "id_user",
            "value" => trim($data_value),
            "raw" => $data_raw,
            "list" => $data_list,
            "readonly" => $data_readonly/* ,
            "reset" => $record->phantom===true ? 1:0 */
        );

    }

    private function _messageTitle($record=null)
    {

        $data_value = $data_raw = "";

        if($record->phantom===false){
            $data_value = $data_raw = $record->title;
        }

        return array(
            "id" => "mailbox-title",
            "name" => "title",
            "value" => trim($data_value),
            "raw" => $data_raw
        );

    }

    private function _messageContent($record=null)
    {
        $data_value = array();
        
        $_indent = 0;
        $_inc = 5;

        if($record->phantom===false){
            $records = \App\Models\Messages::all(array(
                "uid = ?" => $record->uid,
                "received <= ?" => $record->received
            ), array("id_msg","received","text"), "received", "DESC");
        }else{
            return array( "item" => array(
                "html" => ""
            ));
        }

        if(!empty($records)){

            for($i=0;$i<sizeof($records);$i++){

                $_received = $records[$i]->received;
                $_date = $this->translator->trans("messages.mailbox_date_received")." : ".ucfirst(DateMethods::format($_received,"%A %e %B %Y"));
                $_text = $records[$i]->text;

                if($records[$i]->id!=$record->id){
                    
                    $data_value[] = trim("
                        <blockquote style=\"margin-left: {$_indent}px;\">
                            <h4>{$_date}</h4>
                            <span style=\"font-size: 85%;\">{$_text}</span>
                        </blockquote>
                    ");

                }else{

                    $this_msg = trim("
                        <h4>{$_date}</h4>
                        <p class=\"text-default\">{$_text}</p>
                    ");
                    
                }

                $_indent += $_inc;

            }

            array_unshift($data_value,$this_msg);

        }

        $_item = array(
            "tpl" => "row",
            "layout" => STATICS::BS_LAYOUT_1COL,
            "items" => array(
                array(
                    "html" => implode("",$data_value)
                )
            )
        );

        return $this->view->fetchFromString(
            "{% import 'Macros/components-renderer.twig' as bsCmp %}{{ bsCmp.setItems(item) }}",
            array( "item" => $_item )
        );

    }

    private function _messageEdit($record=null)
    {

        $js_script = "
            var _launch = function(){
                var _l = $.jo.getLang();
                var _onChange = function(){
                    \$element.change();
                };
                \$element.wysihtml5({
                    locale: _l,
                    toolbar: {
                        fa: true,
                        size: 'sm',
                        \"font-styles\": true,
                        emphasis: true,
                        lists: true,
                        html: false,
                        link: true,
                        image: false,
                        color: false,
                        blockquote: true
                    },
                    events: {
                        change: _onChange
                    }
                });
            };
            var _onBeforeLaunch = function(){
                //$.jo.debugStop();
                if(!$.fn.wysihtml5){
                    window.setTimeout( () => { _loadScripts(); } , 250 );
                }else{
                    if(\$element){ _launch(); }
                }
            };
            var _loadScripts = function(){
                var _l = $.jo.getLang();
                var _s = ['{{ asset_path(\"wysihtml5_lib\",\"css\",\"vendor\")|join(\"\\',\\'\") }}','{{ asset_path(\"wysihtml5_lib\",\"js\",\"vendor\")|join(\"\\',\\'\") }}'];
                if( _l.indexOf('fr')>-1 || _l.indexOf('nl')>-1 ){ _s.push('/./assets/resources/bootstrap-wysihtml5.'+_l+'.js'); }
                //$.jo.debugStop();
                $.jo.loadScripts( _s , _onBeforeLaunch );
            };
                
            if($){
                if($.fn.wysihtml5){
                    _launch();
                }else{
                    if($.jo.loadScripts){
                        _loadScripts();
                    }else{
                        if(\$element){ \$element.remove(); }
                    }
                }
            }
        ";

        return array(
            "id" => "mailbox-edit",
            "name" => "text",
            "value" => "",
            "raw" => "-1",
            "script" => $this->view->fetchFromString( $js_script , [] )
        );

    }

    private function _attach($record=null,$params=null)
    {
        $fake_data = random_int(0,500)>250 ? 1:0;
        return array(
            "value" => $fake_data,
            "options" => array("classes" => "")
        );
    }

    private function _sentOrReceived($record=null,$params=null)
    {
        $_v = $this->_received($record) ? 0:1; // reçu:envoyé
        return array(
            "value" => $_v,
            "options" => array("filterValue"=>$_v)
        );
    }

    private function _received($record=null)
    {
        $client = $this->client->model;
        if(!is_null($record) && is_object($record)){ $record = (array) $record->raw; }
        return $record["id_user"]==$client->id_user;
    }

}