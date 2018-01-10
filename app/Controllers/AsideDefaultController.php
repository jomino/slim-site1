<?php

namespace App\Controllers;

use App\Models\Ingoing;

use App\Statics\Models as STATICS;

class AsideDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        $partial = array();
        $datas = array();

        $client = $this->client->model;

        $datas["contacts_all"] = $this->_datas(STATICS::CATEGORY_TYPE_USERS,'body_contacts');

        $datas["properties_all"] = $this->_datas(STATICS::CATEGORY_TYPE_PROPERTY,'body_properties');

        $datas["gesloc_all"] = $this->_datas(STATICS::CATEGORY_TYPE_CONTRACT,'body_gesloc');

        $viewmodel = new \App\Models\Views\AsideDefaultViewModel(array(
            "localisation" => $this->container->get('translator'),
            "datas" => $datas
        ));

        $user_nom = $client->getBelongTo("id_user.nom");
        $user_pnom = $client->getBelongTo("id_user.pnom");

        $partial["sidebarmenu"] = array_merge(
            $viewmodel->getItems("sidebarmenu"),
            array( "label_small" => ucfirst($user_nom)."&#160;".ucfirst($user_pnom))
        );

        return $this->view->render($response,"Default/App/Aside/menu.html.twig",$partial);

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