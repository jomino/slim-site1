<?php

namespace App\Controllers;

class LogoutController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {
        //var_dump($request->getAttribute("logged"));
        if($request->getAttribute("logged")!=true){
            return $response->withRedirect("/login");
        }
    }
}
