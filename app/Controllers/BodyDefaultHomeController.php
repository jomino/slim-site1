<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

class BodyDefaultHomeController extends \Core\Controller
{

    private $icons = array(
        STATICS::CATEGORY_TYPE_USERS => "user",
        STATICS::CATEGORY_TYPE_PROPERTY => "home",
        STATICS::CATEGORY_TYPE_CONTRACT => "file-text"
    );

    public function __invoke($request, $response, $args)
    {
        $self = self::class;

        $datas = array(
            "info_box_contacts" => array( $self, "getContactsWidget" ),
            "list_box_contacts" => array( $self, "getContactsInfos" ),
            "pils_box_contacts" => array( $self, "getContactsPils" ),
            "info_box_properties" => array( $self, "getPropertiesWidget" ),
            "pils_box_properties" => array( $self, "getPropertiesPils" ),
            "chart_box_properties" => array( $self, "getPropertiesInfos" ),
            "chart_detail_properties" => array( $self, "getPropertiesDetail" ),
            "info_box_contracts" => array( $self, "getContractsWidget" ),
            "pils_box_contracts" => array( $self, "getContractsPils" ),
            "knobs_box_contracts" => array( $self, "getContractsInfos" ),
            "list_box_contracts" => array( $self, "getContractsDetail" )
        );

        $viewmodel = new \App\Models\Views\HomeDefaultViewModel(array(
            "container" => $this->container,
            "datas" => $datas
        ));

        //$this->logger->debug( self::class, $viewmodel);

        return $this->view->render( $response, "Default/App/Renderer/components-renderer.html.twig", $viewmodel->items );
        
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

    public static function getPropertiesDetail($container)
    {
        $self = self::factory($container);
        return $self->_propertiesDetail();
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

    public static function getContractsDetail($container)
    {
        $self = self::factory($container);
        return $self->_contractsDetail();
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

    public static function getContactsPils($container)
    {
        $self = self::factory($container);
        return $self->_pils(STATICS::CATEGORY_TYPE_USERS);
    }

    public static function getContractsPils($container)
    {
        $self = self::factory($container);
        return $self->_pils(STATICS::CATEGORY_TYPE_CONTRACT);
    }

    public static function getPropertiesPils($container)
    {
        $self = self::factory($container);
        return $self->_pils(STATICS::CATEGORY_TYPE_PROPERTY);
    }

    private function _propertiesDatas()
    {

        $view = $this->container->get("view");
        $logger = $this->container->get("logger");
        $translator = $this->container->get("translator");
        $client = $this->container->get("client")->model;

        $chart_height = 260;

        $chart_default_options = array(
            "layout" => array(
                "padding" => array("top"=>0,"left"=>0,"right"=>0,"bottom"=>10)
            ),
            "legend" => array(
                "display" => false
            ),
            "tooltips" => array(
                "enabled" => false
            ),
            "elements" => array(
                "point" =>array(
                    "radius" => 2,
                    "hoverRadius" => 3
                ),
                "line" =>array(
                    "borderWidth" => 1
                )
            ),
            "scales" => array(
                "xAxes" => array(
                    array(
                        "type" => "time",
                        "distribution" => "series",
                        "time" => array(
                            "unit" => "month",
                            "format" => "YYYY-MM-DD"
                        )
                    )
                ),
                "yAxes" => array(
                    array(
                        "ticks" => array(
                            "beginAtZero" => true,
                            "min" => 0,
                            "max" => 100
                        )
                    )
                )
            )
        );

        $retro_months = 13;

        $this->_datesInit("now -{$retro_months} month midnight");

        $dataset_1 = array(
            "label" => $translator->trans("messages.chart_label_boni"),
            "borderColor" => "#708090",
            "backgroundColor" => "#3c8dbc",
            "fill" => false,
            "data" => $this->_getPlotset($retro_months-1)
        );

        $last_plot = $this->_getNextPlot();

        $pc_today = $last_plot["y"];

        $chart_default_options["title"] = array(
            "display" => true,
            "fontSize" => 14,
            "fontColor" => "#000",
            "text" => $translator->trans("messages.properties_chart_endebit")." : {$pc_today} %"
        );

        return array(
            "id" => "home-properties-chart",
            "type" => "line",
            "styles" => array('width: 100%;','max-height: 260px;'), // using "'" seems to catch a string parser bug with '%' in it (PHP Warning:  Division by zero in ... line 194 )
            "options" => $chart_default_options,
            "data" => array( "datasets" => [$dataset_1] )
        );

    }

    private function _propertiesDetail()
    {
        $translator = $this->container->get("translator");
        return array( "html" => implode( "", array(
            '<blockquote><p class="h6 text-justify">',
            ucfirst($translator->trans("default.lorem_ipsum_md")),
            '</p></blockquote>'
        )));
    }

    private function _getPlotset($months)
    {
        $s = array();
        for($m=0;$m<$months;$m++){
            $s[] = $this->_getNextPlot();
        }
        return $s;
    }

    private function _getNextPlot()
    {
        $_dates = $this->_datesPlotsBound();
        $ingoings = $this->_getPlotsByDates(...$_dates);
        $c_total = sizeof($ingoings);
        $c_plus = 0;
        for($i=0;$i<$c_total;$i++){
            $ingoing = $ingoings[$i];
            $c_plus += $ingoing["creditsum"]>0;
        }
        $pc_plus = number_format(($c_plus/$c_total)*100,2);
        return array( "x" => $_dates[0], "y" => $pc_plus );
    }

    private function _datesPlotsBound($interval="P1M",$format="Y-m-d")
    {
        if(!property_exists($this,"sdtDate")){ $this->_datesInit("now"); }

        $s_date = $this->_datesFormat($format);

        $this->_datesAdd($interval);

        $e_date = $this->_datesFormat($format);

        return [$s_date,$e_date];

    }

    private function _getPlotsByDates($start,$end)
    {

        return $this->_queryIngoing( STATICS::CATEGORY_TYPE_CONTRACT,
            array(
                array( "gesloc", "gesloc.idgesloc=ingoing.id_ref", ["gesloc.idgesloc"]),
                array( "properties", "properties.id_ref=gesloc.idbien", ["properties.name"]),
                array( "geslocpay", "geslocpay.idgesloc=gesloc.idgesloc", ["geslocpay.idpay","geslocpay.debitsum","geslocpay.creditsum"])
            ),
            array(
                "geslocpay.paytype = ?" => 0,
                "geslocpay.dt_debit BETWEEN ? AND ?" => array($start,$end)
            )
        );

    }

    private function _datesInit($date="1970-01-01")
    {
        $this->sdtDate = new \DateTime( $date, new \DateTimeZone("Europe/Brussels"));
        return $this->sdtDate;
    }

    private function _datesAdd($interval)
    {
        return $this->sdtDate->add(new \DateInterval($interval));
    }

    private function _datesFormat($format="Y-m-d")
    {
        return $this->sdtDate->format($format);
    }

    private function _usersDatas()
    {

        $view = $this->container->get("view");
        $logger = $this->container->get("logger");
        $translator = $this->container->get("translator");
        $client = $this->container->get("client")->model;

        $ingoings = $this->_queryIngoing( STATICS::CATEGORY_TYPE_CONTRACT, array(
            array( "gesloc", "gesloc.idgesloc=ingoing.id_ref", ["gesloc.idgesloc","gesloc.endebit"]),
            array( "users", "users.id_ref=gesloc.idloc", ["users.id_user","users.pnom","users.nom"])
        ));

        $u_list_max = 10;

        $u_list = array();

        //$logger->debug("ingoings",["total"=>sizeof($ingoings)]);

        if(!empty($ingoings)){

            for($j=0;$j<sizeof($ingoings);$j++){

                $ingoing = (array) $ingoings[$j];

                if(intval($ingoing["endebit"])==1){

                    $_id = $ingoing["idgesloc"];

                    $ref_model = \App\Models\Geslocpay::first(array(
                        "idgesloc = ?" => $_id,
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

                    $u_content .= $this->_sparklineDatas($_id);

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

        }

        return array(
            "id" => uniqid("users-list-"),
            "list" => $u_list
        );

    }

    private function _sparklineDatas($id)
    {
        $view = $this->container->get("view");
        $translator = $this->container->get("translator");

        $spark_datas = array();
        $spark_values = array();

        $spark_map = json_encode(array(
            "1:" => "#6B8E23",
            "12:" => "#FFD700",
            "24:" => "#CD5C5C",
            "27:" => "#6B8E23"
        ));

        $spark_otions = array(
            "type" => "bar",
            "width" => "100px",
            "height" => "1em",
            "barWidth" => 3,
            "barSpacing" => 1,
            "chartRangeMin" => 1,
            "chartRangeMax" => 31
        );

        $dt = new \DateTime( "now -6 month", new \DateTimeZone("Europe/Brussels"));

        $results = \App\Models\Geslocpay::all(
            array(
                "idgesloc = ?" => $id,
                "paytype = ?" => 0,
                "debitsum > ?" => 0,
                "dt_credit > ?" => $dt->format("Y-m-")."01"
            ),
            array(
                "dt_credit",
                "refpay"
            )
        );

        if(!empty($results)){

            for($j=0;$j<sizeof($results);$j++){

                $result = $results[$j];

                $dt_credit = $result->dt_credit;

                $refpay = $result->refpay;

                $spark_values[] = intval(explode("-",$dt_credit)[2]);

                $spark_datas[] = array(
                    !empty($refpay)?:$translator->trans("default.none"),
                    $dt_credit
                );

            }

        }else{
            //--> todo
        }

        $spark_tooltip = "$.jo.spFormatter('sp-home-contacts',".json_encode($spark_datas).")";

        $spark_colors = "$.range_map({$spark_map})";

        $datas_to_fetch = array(
            "id" => "sp-contacts-{$id}",
            "classes" => array("pull-right"),
            "options" => $spark_otions,
            "values" => $spark_values,
            "tooltip" => $spark_tooltip,
            "colors" => $spark_colors
        );

        return $view->fetch(
            "Default/App/Renderer/sparkline-js.html.twig",
            array( "items" => array($datas_to_fetch) )
        );

    }

    private function _contractsDatas()
    {

        $translator = $this->container->get("translator");
        $logger = $this->container->get("logger");

        $ingoings = $this->_queryIngoing( STATICS::CATEGORY_TYPE_CONTRACT, array(
            array( "gesloc", "gesloc.idgesloc=ingoing.id_ref", ["gesloc.endebit"])
        ));

        $jqknob_datas_default = array(
            "tpl" => "cmp-jqknob",
            "classes" => array("text-center"),
            "raw" => "not_in_use",
            "skin" => "tron",
            "max" => sizeof($ingoings),
            "bgColor" => "#eee",
            "width" => 85,
            "height" => 85,
            //"angleArc" => 360,
            "readonly" => 1,
            //"noInput" => 1,
            "thickness" => 0.1
        );

        $jqknob_items = array();

        if(!empty($ingoings)){

            $gesloc_endebit = 0;
            $gesloc_enboni = 0;
            $gesloc_nocount = 0;

            for($j=0;$j<sizeof($ingoings);$j++){
                $ingoing = (array) $ingoings[$j];
                $endebit = intval($ingoing["endebit"]);
                if($endebit==2){ $gesloc_nocount++; }
                elseif($endebit==1){ $gesloc_endebit++; }
                else{ $gesloc_enboni++; }
            }

            $jqknob_items[] = array_merge( array(
                "id" => "jqknob-enboni",
                "name" => "jqknob-enboni",
                "value" => $gesloc_enboni,
                "fgColor" => "#00a65a",
                "label" => $translator->trans("messages.pos_contracts_knob")
            ), $jqknob_datas_default );

            $jqknob_items[] = array_merge( array(
                "id" => "jqknob-endebit",
                "name" => "jqknob-endebit",
                "value" => $gesloc_endebit,
                "fgColor" => "#ff851b",
                "label" => $translator->trans("messages.neg_contracts_knob")
            ), $jqknob_datas_default );

            $jqknob_items[] = array_merge( array(
                "id" => "jqknob-nocount",
                "name" => "jqknob-nocount",
                "value" => $gesloc_nocount,
                "fgColor" => "#b5bbc8",
                "label" => $translator->trans("messages.neutral_contracts_knob")
            ), $jqknob_datas_default );

        }

        $datas = array( "items" => $jqknob_items );

        //$logger->debug("_contractsDatas()", [print_r($datas,true)]);

        return $datas;

    }

    private function _contractsDetail()
    {
        $translator = $this->container->get("translator");

        $ingoings = $this->_queryIngoing( STATICS::CATEGORY_TYPE_CONTRACT, array(
            array( "gesloc", "gesloc.idgesloc=ingoing.id_ref", ["gesloc.endebit"])
        ));

        $tip_enboni = $translator->trans("messages.tip_gesloc_enboni");
        $tip_endebit = $translator->trans("messages.tip_gesloc_endebit");
        $tip_nocount = $translator->trans("messages.tip_gesloc_nocount");

        $gesloc_endebit = 0;
        $gesloc_enboni = 0;
        $gesloc_nocount = 0;

        for($j=0;$j<sizeof($ingoings);$j++){
            $ingoing = (array) $ingoings[$j];
            $endebit = intval($ingoing["endebit"]);
            if($endebit==2){ $gesloc_nocount++; }
            elseif($endebit==1){ $gesloc_endebit++; }
            else{ $gesloc_enboni++; }
        }

        $datas = implode( "", array(
            '<div class="row">',
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
                '</span>',
            '</div>'
        ));

        return array( "list" => array( array(
            "title" => $translator->trans("messages.home_gesloc_title"),
            "content" => $datas
        )));

    }

    private function _infoBoxDatas($category)
    {

        $client = $this->client->model;
        $translator = $this->container->get("translator");
        $view = $this->container->get("view");

        $datas = array();

        $_count = \App\Models\Ingoing::count( array(
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
                    $datas["title"] = "messages.unlimited_abo_title";
                    $datas["describ"] = $translator->trans("messages.unlimited_abo_describ");
                    $datas["icon"] = array( "fa" => $this->icons[$category] );
                break;
                case 1:
                    $datas["number"] = "Pack free";
                    $datas["progress"] = 100;
                    $datas["title"] = "messages.free_abo_title";
                    $datas["describ"] = $translator->trans("messages.free_abo_describ");
                    $datas["icon"] = array( "fa" => "user-circle" );
                break;
                default:
                    $datas["number"] = $progress." %";
                    $datas["progress"] = $progress;
                    $datas["title"] = "messages.limited_to_abo_title";
                    $datas["describ"] = $translator->trans("messages.limited_to_abo_describ")." {$_count}/{$_max} max.";
                    $datas["icon"] = array( "raw" => $view->fetch(
                        "Default/App/Renderer/charts-renderer.html.twig",
                        array( "items" => array( $this->_gauge($_count,$_max)))
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

    private function _pils($category)
    {
        return array(
            "pils" => array(
                "icon" => $this->icons[$category],
                "styles" => array( "width: 32px;", "height: 32px;"),
                "classes" => array( "bg-blue", "bd-blue")
            )
        );
    }

    private function _queryIngoing($what="",$join=array(),$where=array())
    {

        $client = $this->container->get("client")->model;
        $logger = $this->container->get("logger");

        $where = array_merge( $where, array(
            "ingoing.id_cli = ?" => $client->id_cli,
            "ingoing.id_cat = ?" => $what
        ));

        $ingoing = new \App\Models\Ingoing();

        $query = $ingoing->connector->query()
            ->from($ingoing->table,["ingoing.id_cli"]);

        foreach($where as $k=>$v){
            if(is_array($v)){
                array_unshift($v,$k);
                $query->where(...$v);
            }else{
                $query->where($k,$v);
            }
        }

        for($i=0;$i<sizeof($join);$i++){
            $query->join(...$join[$i]);
        }

        return $query->all();

    }

}