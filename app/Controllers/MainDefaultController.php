<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

class MainDefaultController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {

        $domain = "default";

        $locale = $this->language->getLang();
        $client = $this->client->model;

        if($request->getAttribute("logged")!=true){
            return $response->withRedirect("/login");
        }else{
            
            $elements = array();
            $elements["header"] = $this->_header();
            $elements["aside"] = $this->_aside();
            $elements["content"] = $this->_content();
            $elements["footer"] = $this->_footer();

            $scripts = $this->view->fetch( "Scripts/jqscript.html.twig", array(
                "scripts" => array("/./assets/resources/i18next.js","/./assets/resources/moment_locale_{$locale}.js"),
                "script_done" => implode(" ",array(
                    "if(window.i18next && $.jo.getLang){",
                        "i18next.changeLanguage($.jo.getLang().substr(0,2));",
                    "}"
                ))
            ));

            return $this->view->render( $response, ucfirst($domain)."/app.html.twig", array(
                "favicon" => "/./assets/images/favicon32.png",
                "scripts" => $scripts,
                "domain" => $domain,
                "body" => array(
                    "elements" => $elements
                )
            ));
        }
    }

    protected function _header()
    {
        $logo = array( "logo" => array(
            "href" => $this->router->pathFor('body_home')
        ));

        $navbar = array(
            "loader" => $this->view->fetch( "Scripts/jqloader.html.twig", array(
                "fwHref" => $this->router->pathFor('header_home'),
                "fwFor" => "#default-navbar",
                "fwParams" => ""
            ))
        );

        return array_merge_recursive( $logo, $navbar);

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

        $client = $this->client->model;

        $result = \App\Models\Ingoing::count(array(
            "id_cli = ?" => $client->id_cli
        ));

        $route = $result>0 ? "body_home":"body_start";
        
        $proxy_datas = array(
            "fwHref" => $router->pathFor($route),
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

}