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
                    "url" => $this->router->pathFor("start_edit",["id"=>1])
                ),
                array(
                    "title" => "messages.startup_title_step2",
                    "url" => $this->router->pathFor("start_edit",["id"=>2])
                ),
                array(
                    "title" => "messages.startup_title_step3",
                    "url" => $this->router->pathFor("start_edit",["id"=>3])
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
        return "<p>page content: ".$args["id"]."</p>";
    }
    
    public function save($request, $response, $args)
    {
        return;
    }
}