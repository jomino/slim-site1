<?php

namespace App\Controllers;

use Framework\DateMethods;

use App\Models\Ingoing;

use App\Statics\Models as STATICS;

class HeaderDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        if(isset($args["id"])){
            
            $_method = array($this,"_".$args["id"]);
            
            if(is_callable($_method)){
                $response_datas = call_user_func($_method);
            }else{
                $response_datas = array(
                    "success" => false,
                    "error" => "page_not_found"
                );
            }

            return $response->withJson($response_datas);

        }else{

            $_items = array($this->_link("options"));

            $_calendar = $this->_link("calendar");

            if(!empty($_calendar)){
                array_unshift($_items,$_calendar);
            }

            $_mailbox = $this->_link("mailbox");

            if(!empty($_mailbox)){
                array_unshift($_items,$_mailbox);
            }

            $partial = array( "items" => $_items );

            return $this->view->render( $response, "Default/App/Renderer/navbar-renderer.html.twig", $partial );

        }

    }

    private function _link($type)
    {
        $icons = array(
            "mailbox" => "envelope-o",
            "calendar" => "calendar-o",
            "options" => "cog"
        );

        $nav_id = "li-nav-{$type}";

        switch($type){
            case "options":
                $_link = array(
                    "id" => $nav_id,
                    "icon" => $icons[$type],
                    "href" => $this->router->pathFor("{$type}_view")
                );
            break;
            default:
                $script_run = '
                    var $target = $(\'#'.$nav_id.'\');
                    var _destroy = function(){
                        $.jo.jobScheduler(\''.$type.'-update\');
                        // console.log(\'jobScheduler destroy '.$type.'-update\');
                    }
                    var _processErrors = function(){
                        _destroy();
                        return;
                    };
                    var _processValues = function(response){
                        var _target = $(\'a span[class^="label"]\',$target).first();
                        var _value = parseInt(response.count) || 0;
                        if(_value>0){
                            $target.removeClass(\'hidden\');
                            _target.removeClass(\'label-default label-warning\')
                                .addClass(\'label-warning\').text(_value);
                        }else{
                            if($target.visible()){
                                _target.removeClass(\'label-default label-warning\')
                                    .addClass(\'label-default\').text(\'0\');
                                $target.addClass(\'hidden\');
                            }
                        }
                        return;
                    };
                    var _loadValues = function(){
                        $.jo.jqXhr(
                            \''.($this->router->pathFor("header_home",["id"=>$type])).'\',
                            null,
                            _processValues,
                            _processErrors,
                            \'json\',
                            \'post\'
                        );
                    };
                    var _init = function(){
                        $.jo.jobScheduler(
                            _loadValues, // scheduled callback
                            3*60000, // interval de 3 min
                            this, // scope
                            true, // persistent
                            \''.$type.'-update\'
                        );
                        $.jo.jobScheduler(
                            _loadValues,
                            1000,
                            this
                        );
                    };
                    //$.jo.debugStop();
                    _init();
                ';

                $_link = array(
                    "id" => $nav_id,
                    "icon" => $icons[$type],
                    "classes" => array("hidden"),
                    "script" => $this->view->fetch(
                        "Scripts/jqready.html.twig",
                        array( "script_run" => $script_run )
                    ),
                    "href" => $this->router->pathFor("{$type}_view"),
                    "tagged" => array(
                        "classes" => array("label-default"),
                        "text" => "0"
                    )
                );

        }

        return $_link;

    }

    private function _datas($type,$route)
    {

        $_count = $this->_count($type);

        return array(
            "href" => $_count ? $this->router->pathFor($route):"#",
            "widget" => array(
                "label" => "{$_count}",
                "cls" => "pull-right bg-".( $_count ? "green":"orange")
            )
        );
        
    }

    private function _count($type)
    {

        $client = $this->client->model;

        return Ingoing::count(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => $type
        ));

    }

    private function _calendar()
    {

        $client = $this->client->model;

        $result = \App\Models\Calendars::first(array(
            "id_cli = ?" => $client->id_cli,
            "id_caltype = ?" => STATICS::CALENDAR_TYPE_LOCAL
        ));

        if(!empty($result)){

            $count = \App\Models\Calevents::count( array(
                "id_cal = ?" => $result->id,
                "start >= ?" => DateMethods::now()
            ));
            
            return array(
                "success" => true,
                "count" => $count
            );

        }

        return array(
            "success" => false,
            "error" => "calendar_not_found"
        );

    }

    private function _mailbox()
    {

        $client = $this->client->model;

        $count = \App\Models\Messages::count( array(
            "id_user = ?" => $client->id_user,
            "proceed = ?" => STATICS::MESSAGE_TYPE_NOTREAD
        ));
        
        return array(
            "success" => true,
            "count" => $count+0
        );

    }

}