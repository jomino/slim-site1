<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

class BodyDefaultHomeController extends \Core\Controller
{

    public function __invoke($request, $response, $args)
    {
        $self = self::class;

        $datas = array(
            "info_box_contacts" => array( $self, "getContactsWidget" ),
            "describ_box_contacts" => array( $self, "getContactsInfos" ),
            "info_box_properties" => array( $self, "getPropertiesWidget" ),
            "info_box_contracts" => array( $self, "getContractsWidget" ),
            "describ_box_contracts" => array( $self, "getContractsInfos" )
        );

        $viewmodel = new \App\Models\Views\HomeDefaultViewModel(array(
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

    public static function getPropertiesInfos($container)
    {
        $self = self::factory($container);
        return $self->_propertiesDatas();
    }

    public static function getContactsInfos($container)
    {
        $self = self::factory($container);
        return $self->_usersDatas();
    }

    public static function getContractsInfos($container)
    {
        $self = self::factory($container);
        return $self->_contractsDatas();
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

    private function _propertiesDatas()
    {

        $view = $this->container->get("view");
        $logger = $this->container->get("logger");
        $translator = $this->container->get("translator");
        $client = $this->container->get("client")->model;

        $start_date = new \DateTime("now -1 year", new \DateTimeZone("Europe/Brussels"));

    }

    private function _usersDatas()
    {

        $view = $this->container->get("view");
        $logger = $this->container->get("logger");
        $translator = $this->container->get("translator");
        $client = $this->container->get("client")->model;

        $where = array(
            "ingoing.id_cli = ?" => $client->id_cli,
            "ingoing.id_cat = ?" => STATICS::CATEGORY_TYPE_CONTRACT
        );

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            $query->where($k,$v);
        }

        $query->join("gesloc","gesloc.idgesloc=ingoing.id_ref",["gesloc.idgesloc","gesloc.endebit"]);
        $query->join("users","users.id_ref=gesloc.idloc",["users.id_user","users.pnom","users.nom"]);

        $ingoings = $query->all();

        $u_list_max = 10;

        //$logger->debug("ingoings",["total"=>sizeof($ingoings)]);

        $boxies = array();

        if(!empty($ingoings)){

            $u_list = array();

            for($j=0;$j<sizeof($ingoings);$j++){

                $ingoing = (array) $ingoings[$j];

                if(intval($ingoing["endebit"])==1){

                    $ref_model = \App\Models\Geslocpay::first(array(
                        "idgesloc = ?" => $ingoing["idgesloc"],
                        "debitsum > ?" => 0
                    ), array("MAX(dt_credit)"=>"dt_credit"));

                    $char_max = 24;

                    $u_name = ucfirst($ingoing["pnom"]).(!empty($ingoing["pnom"])?" ":"").ucfirst($ingoing["nom"]);

                    if(strlen($u_name)>$char_max){ $u_name = substr($u_name,0,$char_max)."&#160;&hellip;&#160;&#160;"; }

                    $u_content = implode( "", array(
                        '<span class="h6">',
                                "{$u_name}&#160;&#160;",
                            '<small>',
                                '(&#160;', $translator->trans("messages.users_endebit_lastpaid"), '&#160;',
                                !empty($ref_model) ? $ref_model->display->dt_credit."&#160;":"", ')',
                            '</small>',
                        '</span>'
                    ));

                    $u_list[] = array(
                        "id" => "list-item-".$ingoing["id_user"],
                        "content" => $u_content
                    );

                }

            }

            $u_list = array_slice( $u_list, 0, $u_list_max-1);
        
            $link_read_more = implode( "", array(
                '<a href="#">',
                    $translator->trans("default.read_more"),
                    '&#160;&#160;<span class="fa fa-arrow-right"></span>',
                '</a>'
            ));

            $u_list[] = array(
                "id" => uniqid("link-readmore-"),
                "content" => $link_read_more
            );

            $name_to_fetch = "datas_to_fetch";

            $datas_to_fetch = array(
                "id" => uniqid("users-list-"),
                "tpl" => "list-unstyled",
                "items" => $u_list
            );

            $content = $view->fetchFromString(
                implode( "", array(
                    "{% import 'Macros/Default/dlist.twig' as Dlist %}",
                    "{{ Dlist.setItems({$name_to_fetch}) }}"
                )),
                array( $name_to_fetch => $datas_to_fetch )
            );

        }

        $boxies[] = array(
            "title" => $translator->trans("messages.home_users_endebit"),
            "content" => $content
        );

        return array(
            "body" => array(
                "tpl" => "dl-list",
                "items" => $boxies
            )
        );

    }

    private function _contractsDatas()
    {

        $translator = $this->container->get("translator");
        $client = $this->container->get("client")->model;

        $where = array(
            "ingoing.id_cli = ?" => $client->id_cli,
            "ingoing.id_cat = ?" => STATICS::CATEGORY_TYPE_CONTRACT
        );

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            $query->where($k,$v);
        }

        $query->join("gesloc","gesloc.idgesloc=ingoing.id_ref",["gesloc.endebit"]);

        $ingoings = $query->all();

        $boxies = array();

        if(!empty($ingoings)){

            $tip_enboni = $translator->trans("messages.tip_gesloc_enboni");
            $tip_endebit = $translator->trans("messages.tip_gesloc_endebit");
            $tip_nocount = $translator->trans("messages.tip_gesloc_nocount");

            $gesloc_endebit = 0;
            $gesloc_enboni = 0;
            $gesloc_nocount = 0;

            for($j=0;$j<sizeof($ingoings);$j++){
                $ingoing = (array) $ingoings[$j];
                $endebit = intval($ingoing["endebit"]);
                if($endebit==2){ $gesloc_endebit++; }
                elseif($endebit==1){ $gesloc_nocount++; }
                else{ $gesloc_enboni++; }
            }

            $boxies[] = array(
                "title" => $translator->trans("messages.home_gesloc_title"),
                "content" => implode("",array(
                    '<span class="label label-default" data-toggle="tooltip" ',
                            'title="'.$tip_enboni.'">',
                        '<span class="h5 bold">'.$gesloc_enboni.'</span>',
                        '<span class="text-green">&#160;&#160;<i class="fa fa-thumbs-up"></i></span>',
                    '</span>',
                    '<span>&#160;&#160;&#160;&#160;</span>',
                    '<span class="label label-default" data-toggle="tooltip" ',
                            'title="'.$tip_endebit.'">',
                        '<span class="h5 bold">'.$gesloc_endebit.'</span>',
                        '<span class="text-red">&#160;&#160;<i class="fa fa-thumbs-down"></i></span>',
                    '</span>',
                    '<span>&#160;&#160;&#160;&#160;</span>',
                    '<span class="label label-default" data-toggle="tooltip" ',
                            'title="'.$tip_nocount.'">',
                        '<span class="h5 bold">'.$gesloc_nocount.'</span>',
                        '<span class="text-blue">&#160;&#160;<i class="fa fa-chain-broken"></i></span>',
                    '</span>'
                ))
            );

        }

        return array(
            "body" => array(
                "tpl" => "dl-list",
                "horizontal" => 1,
                "items" => $boxies
            )
        );

    }

    private function _infoBoxDatas($category)
    {

        $client = $this->client->model;
        $translator = $this->container->get("translator");
        $view = $this->container->get("view");

        $datas = array();

        $_count = \App\Models\Ingoing::count(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => $category
        ));

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

        $result = \App\Models\Accounts::first(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => $type
        ));

        if(!empty($result)){
            return (int) $result->getBelongTo("id_actype.seats");
        }

        return null;

    }

}