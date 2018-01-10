<?php

namespace App\Controllers;

use App\Auth\Auth as Auth;

class LoginController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {
        //var_dump($request->getAttribute("logged"));
        if($request->getAttribute("logged")!=true){
            $with_error = array();
            return $this->view->render( $response, "Home/login.html.twig", $with_error);
        }else{
            $model = $this->client->model;
            $group = $model->getBelongTo("id_grp.ref_grp");
            return $response->withRedirect("/{$group}/main");
        }
    }
}
