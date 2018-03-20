<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

class BodyDefaultStartupController extends \Core\Controller
{
    private $views_path = "Default/App/Content";

    public function __invoke($request, $response, $args)
    {

        $jqsteps_data = array(
            "id" => uniqid("steps-"),
            "steps" => array(
                array(
                    "title" => "messages.startup_title_step1",
                    "url" => $this->router->pathFor("start_edit",["id"=>STATICS::CATEGORY_TYPE_USERS])
                ),
                array(
                    "title" => "messages.startup_title_step2",
                    "url" => $this->router->pathFor("start_edit",["id"=>STATICS::CATEGORY_TYPE_PROPERTY])
                ),
                array(
                    "title" => "messages.startup_title_step3",
                    "url" => $this->router->pathFor("start_edit",["id"=>STATICS::CATEGORY_TYPE_CONTRACT])
                )
            )
        );

        $box_body = $this->view->fetchFromString("
            {% import 'Macros/jq-steps.twig' as jqSteps %}
            {{- jqSteps.render(items) -}}
        ", array(
            "items" => $jqsteps_data
        ));

        $box_datas = array(
            "items" => array(
                array(
                    "id" => uniqid("box-"),
                    "title" => "messages.box_startup_title",
                    "body" => $box_body
                )
            )
        );

        return $this->view->render( $response, "{$this->views_path}/Startup/edit-bs.html.twig", $box_datas);
        
    }

    public function edit($request, $response, $args)
    {
        switch((int) $args["id"]){
            case STATICS::CATEGORY_TYPE_USERS: return $this->_users();
            case STATICS::CATEGORY_TYPE_PROPERTY: return $this->_propeties();
            case STATICS::CATEGORY_TYPE_CONTRACT: return $this->_contract();
            default: return "<p>404 not found !!</p>";
        }
    }
    
    public function save($request, $response, $args)
    {
        return $response->withJson(array("success" => true, "messages" => "not yet implemented"));
    }
    
    private function _propeties()
    {
        return "<p><span class=\"fa fa-exclamation-triangle jo-text-yellow\"></span>&#160;&#160;work in progress ...</p>";
    }
    
    private function _users()
    {
        return "<p><span class=\"fa fa-exclamation-triangle jo-text-yellow\"></span>&#160;&#160;work in progress ...</p>";
    }
    
    private function _contract()
    {
        return "<p><span class=\"fa fa-exclamation-triangle jo-text-yellow\"></span>&#160;&#160;work in progress ...</p>";
    }

}