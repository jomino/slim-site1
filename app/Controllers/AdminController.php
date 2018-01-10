<?php

namespace App\Controllers;

use App\Auth\Auth as Auth;

class AdminController extends \Core\Controller
{
    
    public function __invoke($request, $response, $args)
    {
        $elements = array();
        $elements["contacts"] = $this->_contacts();
        $elements["properties"] = $this->_properties();
        $elements["gesloc"] = $this->_gesloc();
        $elements["geslocpay"] = $this->_geslocpay();
        $elements["geslochisto"] = $this->_geslochisto();
        $elements["geslocdoc"] = $this->_geslocdoc();
        return $this->view->render( $response, 'Admin/app.html.twig', array(
            "body" => array(
                "elements" => $elements
            )
        ));
    }

    private function _contacts()
    {

        $client = $this->client->model;

        $partial = array();
        $links = array();
        
        $ingo = \App\Models\Ingoing::count(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_USERS
        ));

        $partial["title"] = sprintf("Users: %u recs",$ingo);
        $partial["message"] = "message";

        $base_path = "/admin/users/confirm";

        if($ingo>0){
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/update", "text"=>"admin.users_update");
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/delete", "text"=>"admin.users_delete");
        }else{
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/import", "text"=>"admin.users_import");
        }

        $partial["links"] = $links;

        return $partial;

    }

    private function _properties()
    {

        $client = $this->client->model;

        $partial = array();
        $links = array();
        
        $ingo = \App\Models\Ingoing::count(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_PROPERTY
        ));

        $partial["title"] = sprintf("Properties: %u recs",$ingo);
        $partial["message"] = "message";
        
        $base_path = "/admin/properties/confirm";

        if($ingo>0){
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/update", "text"=>"admin.properties_update");
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/delete", "text"=>"admin.properties_delete");
        }else{
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/import", "text"=>"admin.properties_import");
        }

        $partial["links"] = $links;

        return $partial;

    }

    private function _gesloc()
    {

        $client = $this->client->model;

        $partial = array();
        $links = array();
        
        $ingo = \App\Models\Ingoing::count(array(
            "id_cli = ?" => $client->id_cli,
            "id_cat = ?" => \App\Statics\Models::CATEGORY_TYPE_CONTRACT
        ));

        $partial["title"] = sprintf("Gesloc: %u recs",$ingo);
        $partial["message"] = "message";

        $base_path = "/admin/gesloc/confirm";

        if($ingo>0){
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/delete", "text"=>"admin.gesloc_delete");
        }else{
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/import", "text"=>"admin.gesloc_import");
        }

        $partial["links"] = $links;

        return $partial;

    }

    private function _geslocpay()
    {

        $client = $this->client->model;

        $partial = array();
        $links = array();
        
        $ingo = \App\Models\Geslocpay::count(array(
            "agence = ?" => $client->uri
        ));

        $partial["title"] = sprintf("Geslocpay: %u recs",$ingo);
        $partial["message"] = "message";

        $base_path = "/admin/geslocpay/confirm";

        if($ingo>0){
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/delete", "text"=>"admin.geslocpay_delete");
        }else{
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/import", "text"=>"admin.geslocpay_import");
        }

        $partial["links"] = $links;

        return $partial;

    }

    private function _geslochisto()
    {

        $client = $this->client->model;

        $partial = array();
        $links = array();
        
        $ingo = \App\Models\Geslochisto::count(array(
            "agence = ?" => $client->uri
        ));

        $partial["title"] = sprintf("Geslochisto: %u recs",$ingo);
        $partial["message"] = "message";

        $base_path = "/admin/geslochisto/confirm";

        if($ingo>0){
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/delete", "text"=>"admin.geslochisto_delete");
        }else{
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/import", "text"=>"admin.geslochisto_import");
        }

        $partial["links"] = $links;

        return $partial;

    }

    private function _geslocdoc()
    {

        $client = $this->client->model;

        $partial = array();
        $links = array();
        
        $ingo = \App\Models\Geslocdoc::count(array(
            "agence = ?" => $client->uri
        ));

        $partial["title"] = sprintf("Geslocdoc: %u recs",$ingo);
        $partial["message"] = "message";

        $base_path = "/admin/geslocdoc/confirm";

        if($ingo>0){
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/delete", "text"=>"admin.geslochisto_delete");
        }else{
            $links[] = array( "type"=>"get", "href"=>"{$base_path}/import", "text"=>"admin.geslochisto_import");
        }

        $partial["links"] = $links;

        return $partial;

    }

}