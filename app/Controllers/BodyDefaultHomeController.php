<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

use App\Models\Ingoing;
use App\Models\Accounts;

use App\Models\Views\HomeDefaultViewModel;

class BodyDefaultHomeController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {

        $datas = array(
            "info_box_contacts" => array( self::class, "getContactsWidget" ),
            "info_box_properties" => array( self::class, "getPropertiesWidget" ),
            "info_box_contracts" => array( self::class, "getContractsWidget" )
            //,"describ_box_contacts" => array( self::class, "getContactsInfos" )
        );

        $viewmodel = new HomeDefaultViewModel(array(
            "container" => $this->container,
            "datas" => $datas
        ));

        //$this->logger->debug( self::class, $viewmodel);

        return $this->view->render( $response, "Default/App/Renderer/widgets-renderer.html.twig", $viewmodel->items );
        
    }

    public static function factory($container)
    {
        return new static($container);
    }

    public static function getContactsInfos($container)
    {
        $self = self::factory($container);
        return $self->_contactsDatas();
    }

    public static function getContactsWidget($container)
    {
        $self = self::factory($container);
        return $self->_infoBoxDatas(STATICS::CATEGORY_TYPE_USERS);
    }

    public static function getPropertiesWidget($container)
    {
        $self = self::factory($container);
        return $self->_infoBoxDatas(STATICS::CATEGORY_TYPE_PROPERTY);
    }

    public static function getContractsWidget($container)
    {
        $self = self::factory($container);
        return $self->_infoBoxDatas(STATICS::CATEGORY_TYPE_CONTRACT);
    }

    private function _contactsDatas($category)
    {

    }

    private function _infoBoxDatas($category)
    {

        $translator = $this->container->get("translator");
        $view = $this->container->get("view");

        // light-blue

        $datas = array();

        $_count = $this->_count($category);
        $_max = $this->_max($category);
        
        if(is_int($_max)){
            $progress = intval(($_count/$_max)*100);
            switch($_max){
                case 9999:
                    $datas["number"] = $_count." ".$translator->trans("default.entries");
                    $datas["progress"] = $progress;
                    $datas["text"] = $translator->trans("messages.unlimited_abo_title");
                    $datas["describ"] = $translator->trans("messages.unlimited_abo_describ");
                    $datas["icon"] = array( "fa" => "users" );
                break;
                case 1:
                    $datas["number"] = "Pack free";
                    $datas["progress"] = 100;
                    $datas["text"] = $translator->trans("messages.free_abo_title");
                    $datas["describ"] = $translator->trans("messages.free_abo_describ");
                    $datas["icon"] = array( "fa" => "user-circle" );
                break;
                default:
                    $datas["number"] = $progress." %";
                    $datas["progress"] = $progress;
                    $datas["text"] = $translator->trans("messages.limited_to_abo_title");
                    $datas["describ"] = $translator->trans("messages.limited_to_abo_describ")." {$_count}/{$_max} max.";
                    $datas["icon"] = array( "raw" => $view->fetch(
                        "Default/App/Renderer/charts-renderer.html.twig",
                        array( "items" => array(
                            $this->_gauge($_count,$_max)
                        ))
                    ));
            }
        }

        return $datas;

    }

    private function _gauge($value,$max)
    {
        $default_tpl = "gauge-js";
        $default_height = "64px";
        $default_id = uniqid("stats-");
        return array(
            "tpl" => $default_tpl,
            "id" => $default_id,
            "height" => $default_height,
            "value" => $value,
            "max" => $max,
            "classes" => array("margin-5"),
            "options" => array(
                "angle" => 0.15,
                "lineWidth" => 0.25,
                "pointer" => array(
                    "length" => 0.4
                )
            )
        );
    }

    private function _max($type)
    {

        $client = $this->client->model;

        $result = Accounts::first(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => $type
        ));

        if(!empty($result)){
            return (int) $result->getBelongTo("id_actype.seats");
        }

        return null;

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