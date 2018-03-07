<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

use Framework\ArrayMethods as ArrayMethods;
use Framework\DateMethods as DateMethods;

class BodyDefaultCalendarController extends \Core\Controller
{
    private $views_path = "Default/App";
    
    private $icons = array(
        "mailbox" => "envelope-o",
        "calendar" => "calendar-o"
    );

    public function home($request, $response, $args)
    {

        $calendar = $this->_calendar(STATICS::CALENDAR_TYPE_LOCAL);

        $id_cal = $calendar->id_cal;

        $tline_header = array(
            "title" => $calendar->title,
            "small" => $calendar->description
        );

        $full_cal_tline = array(
            "header" => $tline_header,
            "timeline" => $this->_timeline($id_cal)
        );

        $full_cal_datas = array( "item" => array(
            "id" => "calendar-view",
            "url" => $this->router->pathFor( 'calendar_pipe', array( "id" => $id_cal ))
        ));

        $full_cal_html = array(
            "html" => $this->view->fetch( $this->views_path."/Content/Calendar/fullcal.html.twig", $full_cal_datas)
        );
        
        $datas = array(
            "full_cal_html" => $full_cal_html,
            "full_cal_tline" => $full_cal_tline
        );

        $wrapper = new \App\Models\Views\CalendarDefaultViewModel( array(
            "container" => $this->container,
            "datas" => $datas
        ));

        return $this->view->render($response, $this->views_path."/Renderer/components-renderer.html.twig", $wrapper->getItems() );

    }

    public function pipe($request, $response, $args)
    {

        if(isset($args["id"])){

            $events = array();

            $params = (array) $request->getParsedBody();
    
            $start = $params["start"];
            $end = $params["end"];
            $id = $args["id"];
    
            $results = $this->_events( $id, $start, $end, array( "id_cevtype != ?" => STATICS::CALEVENT_TYPE_REMINDER));
    
            if(!empty($results)){
                for($i=0;$i<sizeof($results);$i++){
                    $result = $results[$i];
                    $events[] = array(
                        "id" => "event-".$result->id_cev,
                        "className" => "text-black",
                        "title" => $result->title,
                        "color" => $result->getBelongTo("id_cevtype.color"),
                        "start" => $result->start,
                        "end" => $result->end,
                        "allDay" => !DateMethods::compare($result->start,$result->end)
                    );
                }
            }
    
            $response_datas = array(
                "success" => true,
                "events" => $events
            );

        }else{

            $response_datas = array(
                "success" => false,
                "error" => "missing_calendar_id"
            );

        }

        return $response->withJson($response_datas);

    }

    private function _timeline($id)
    {

        $limit = 10;

        $timeline = array();

        $now = DateMethods::now("en",true);
        
        $results = $this->_events( $id, $now, "", array( "id_cevtype = ?" => STATICS::CALEVENT_TYPE_REMINDER),$limit);
    
        $events_by_dates = array();
        
        if(!empty($results)){

            $_count = sizeof($results);

            for($i=0;$i<$_count;$i++){

                $result = $results[$i];

                $date_key = explode(" ",$result->display->start)[0];

                if(!in_array($date_key,array_keys($events_by_dates))){
                    $events_by_dates[$date_key] = array();
                }

                array_push( $events_by_dates[$date_key], array(
                    "icon" => "info-circle",
                    "content" => array(
                        "time" => DateMethods::format($result->start,"H:i"),
                        "header" => array( "title" => $result->title ),
                        "body" => $result->description
                    )
                ));

            }
            
            if(!empty($events_by_dates)){

                foreach($events_by_dates as $label=>$items){

                    $timeline[] = array(
                        "label" => array(
                            "classes" => array("bg-light-blue","flat","fixed-width","text-center"),
                            "text" => $label
                        )
                    );

                    $timeline = array_merge( $timeline, $items);

                }

                $timeline[] = array(
                    "label" => array(
                        "classes" => array("bg-light-blue","flat","fixed-width","text-center"),
                        "text" => "..."
                    )
                );

            }

        }
            
        return $timeline;

    }

    private function _events($id,$start,$end="",$where=array(),$limit=null)
    {
        return \App\Models\Calevents::all(
            array_merge( array(
                "id_cal = ?" => $id,
                "start >= ?" => $start
            ),
            $where,
            $end!="" ? array("end <= ?"=>$end):array()
        ), array("*"), "start", "asc", $limit, 0);
    }

    private function _calendar($type)
    {

        $client = $this->client->model;

        return \App\Models\Calendars::first(array(
            "id_cli = ?" => $client->id_cli,
            "id_caltype = ?" => $type
        ));

    }

    private function _pils($type)
    {
        return array(
            "pils" => array(
                "icon" => $this->icons[$type],
                "styles" => array( "width: 32px;", "height: 32px;"),
                "classes" => array( "bg-blue", "bd-blue")
            )
        );
    }

}