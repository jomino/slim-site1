<?php

namespace App\Controllers;

class MainDefaultController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {
        if($request->getAttribute("logged")!=true){
            return $response->withRedirect("/login");
        }else{
            
            $elements = array();
            $elements["header"] = $this->_header();
            $elements["aside"] = $this->_aside();
            $elements["content"] = $this->_content();
            $elements["footer"] = $this->_footer();

            $scripts = $this->view->fetch( "Scripts/jqscript.html.twig", array(
                "scripts" => array("/./assets/resources/i18next.js"),
                "script_done" => implode(" ",array(
                    "if(window.i18next && $.jo.getLang){",
                        "i18next.changeLanguage($.jo.getLang().substr(0,2));",
                    "}"
                ))
            ));

            return $this->view->render( $response, "Default/app.html.twig", array(
                "favicon" => "/./assets/images/favicon32.png",
                "scripts" => $scripts,
                "body" => array(
                    "elements" => $elements
                )
            ));
        }
    }

    protected function _header()
    {
        return 1;
    }

    protected function _aside()
    {

        $router = $this->router;
        $client = $this->client->model;

        $partial = array();
        $datas = array();

        if($client->id_lvl==\App\Statics\Models::LEVEL_TYPE_ADMIN ||
            $client->id_lvl==\App\Statics\Models::LEVEL_TYPE_NONE){

            $links_admin = array();

            $links_admin[] = array(
                "href" => $router->pathFor('admin'),
                "text" => "default.sync"
            );

            $partial["links_admin"] = $links_admin;

        }
        
        $partial["link_quit"] = $router->pathFor('logout');

        /*print("<pre>");
        print_r($partial);
        print("</pre>");*/
        
        $proxy_datas = array(
            "fwHref" => $router->pathFor('aside_home'),
            "fwFor" => "#default-menu",
            "fwParams" => ""
        );

        $partial["loader"] = $this->view->fetch( "Scripts/jqloader.html.twig", $proxy_datas );

        return $partial;
    }

    protected function _content()
    {

        $router = $this->container->get('router');
        
        $proxy_datas = array(
            "fwHref" => $router->pathFor('body_home'),
            "fwFor" => ".content",
            "fwParams" => ""
        );

        $loader = $this->view->fetch( "Scripts/jqloader.html.twig", $proxy_datas );

        return array(
            "loader" => $loader
        );

    }

    protected function _footer()
    {
        return array(
            "version" => "1.0.0"
        );

    }
    protected function _asideContacts()
    {

    }

}