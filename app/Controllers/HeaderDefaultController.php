<?php

namespace App\Controllers;

use App\Models\Ingoing;

use App\Statics\Models as STATICS;

class HeaderDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {
        if(isset($args["id"])){
            $response_datas = array("success"=>true,"count"=>5);
            return $response->withJson($response_datas);
        }else{

            $partial = array(
                "items" => array(
                    $this->_link("mailbox"),
                    $this->_link("calendar")
                )
            );

            return $this->view->render( $response, "Default/App/Renderer/navbar-renderer.html.twig", $partial );

        }

    }

    private function _link($type)
    {
        $icons = array(
            "mailbox" => "envelope-o",
            "calendar" => "calendar-o"
        );

        $nav_id = "li-nav-{$type}";

        $script_run = '
            var $target = $(\'#'.$nav_id.'\');
            var _destroy = function(){
                $.jo.jobScheduler(\''.$type.'-update\');
                console.log(\'jobScheduler destroy '.$type.'-update\');
            }
            var _processErrors = function(){
                _destroy();
                return;
            };
            var _processValues = function(response){
                var _target = $(\'a span[class*=label]\',$target).first();
                var _value = response.count || 0;
                _target.toggleClass( _value>0 ? \'label-warning\':\'label-default\')
                    .text(_value);
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
            $.jo.debugStop();
            _init();
        ';

        $nav_script = $this->view->fetch(
            "Scripts/jqready.html.twig",
            array( "script_run" => $script_run )
        );

        return array(
            "id" => $nav_id,
            "icon" => $icons[$type],
            "script" => $nav_script,
            "href" => $this->router->pathFor("body_{$type}"),
            "tagged" => array(
                "classes" => array("label-default"),
                "text" => "0"
            )
        );

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

}