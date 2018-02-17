<?php

namespace App\Controllers;

class GeslocConfirmDefaultController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {
        if(empty($args["op"])){
            return;
        }

        switch($args["op"])
        {
            case "import":
                $confirm = $this->_import();
            break;
            case "update":
                $confirm = $this->_update();
            break;
            case "delete":
                $confirm = $this->_delete();
            break;
            default:
                $confirm = array(
                    "title" => "Unknow method",
                    "message" => "The '{$args["op"]}' method is not valid.",
                    "links" => [$this->_getAnnul()]
                );
        }

        return $this->view->render( $response, 'Admin/app.html.twig', array(
            "body" => array(
                "elements" => array(
                    "confirm" => $confirm
                )
            )
        ));

    }

    protected function _import()
    {
        $router = $this->router;

        $partial = array();

        $partial["title"] = "admin.gesloc_import";
        $partial["message"] = "Attention blabla ...";

        $partial["links"] = $this->_getLinks($router->pathFor("gesloc_import"));

        return $partial;

    }

    protected function _update()
    {
        $router = $this->container->get('router');

        $partial = array();
        $links = array();

        $partial["title"] = "admin.gesloc_update";
        $partial["message"] = "Attention blabla ...";

        $partial["links"] = $this->_getLinks($router->pathFor("gesloc_update"));

        return $partial;
        
    }

    protected function _delete()
    {
        $router = $this->container->get('router');

        $partial = array();

        $partial["title"] = "admin.gesloc_delete";
        $partial["message"] = "Attention blabla ...";

        $partial["links"] = $this->_getLinks($router->pathFor("gesloc_delete"));

        return $partial;
        
    }

    protected function _getLinks($href)
    {

        $links = array(
            $this->_getPositif($href),
            $this->_getAnnul()
        );

        return $links;
    }

    protected function _getPositif($href)
    {

        $client = $this->client->model;

        $domain = $client->getBelongTo("id_grp.ref_grp");

        $id_link_go = "btn-confirm";
        $text_link_go = "messages.confirm";
        $script_link_go = implode( " ", array(
            "$('a#{$id_link_go}').on( 'click', function(){",
                "$.jo.loadDatas( '{$href}','','', function(){",
                    "$.jo.reloadPage('{$domain}');",
                "});",
            "});"
        ));

        return array(
            "id" => $id_link_go,
            "on_click" => $script_link_go,
            "text" => $text_link_go
        );

    }

    protected function _getAnnul()
    {

        $id_link_no = "btn-annul";
        $text_link_no = "messages.skip";
        $script_link_no = implode( "", array(
            "$('a#{$id_link_no}').on( 'click', function(){ ",
                "$.jo.reloadBody();",
            " });"
        ));

        return array(
            "id" => $id_link_no,
            "on_click" => $script_link_no,
            "text" => $text_link_no
        );

    }

}

