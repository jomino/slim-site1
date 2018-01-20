<?php

namespace App\Controllers;

class LoginController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {
        //var_dump($request->getAttribute("logged"));
        if($request->getAttribute("logged")!=true){
            $with_error = array();
            if($request->getAttribute("error_login")==true){
                $with_error = array(
                    "flash" => $this->translator->trans("messages.flash_error_login")
                );
            }
            return $this->view->render( $response, "Home/login.html.twig", $with_error);
        }else{
            $model = $this->client->model;
            $group = $model->getBelongTo("id_grp.ref_grp");
            return $response->withRedirect("/{$group}/main");
        }
    }
}
