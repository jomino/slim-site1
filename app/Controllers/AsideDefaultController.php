<?php

namespace App\Controllers;

use App\Models\Ingoing;

use App\Statics\Models as STATICS;

class AsideDefaultController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        $partial = array();

        $datas = array(
            "heading_title" => $this->_menuTitle(),
            "contacts_all" => $this->_datas(STATICS::CATEGORY_TYPE_USERS,'body_contacts'),
            "properties_all" => $this->_datas(STATICS::CATEGORY_TYPE_PROPERTY,'body_properties'),
            "contracts_all" => $this->_datas(STATICS::CATEGORY_TYPE_CONTRACT,'body_gesloc'),
            "calendar_all" => $this->_calendar('calendar_view')/* ,
            "mailbox_all" => $this->_link('mailbox_view') */
        );

        $viewmodel = new \App\Models\Views\AsideDefaultViewModel(array(
            "datas" => $datas
        ));

        //$partial = array( "items" => $viewmodel->getItems() );

        return $this->view->render( $response, "Default/App/Renderer/sidebar-renderer.html.twig", $viewmodel->getItems() );

    }

    private function _menuTitle()
    {

        $client = $this->client->model;

        $user_nom = $client->getBelongTo("id_user.nom");
        $user_pnom = $client->getBelongTo("id_user.pnom");

        return array(
            "heading" => array(
                "label_small" => implode( "&#160;", array(
                    ucfirst($user_nom),
                    ucfirst($user_pnom)
                ))
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

    private function _calendar()
    {

        $client = $this->client->model;

        $result = \App\Models\Calendars::first(array(
            "id_cli = ?" => $client->id_cli,
            "id_caltype = ?" => STATICS::CALENDAR_TYPE_LOCAL
        ));

        return array(
            "href" => $this->router->pathFor('calendar_view',array("id"=>$result->id))
        );
    }

}