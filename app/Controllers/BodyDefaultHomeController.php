<?php

namespace App\Controllers;

class BodyDefaultHomeController extends \Core\Controller
{

    public function home($request, $response, $args)
    {
        return $this->view->render($response,"Default/App/Content/Tempo/tempo.html.twig");
    }

}