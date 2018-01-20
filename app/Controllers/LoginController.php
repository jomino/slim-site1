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
                    "flash" => $this->_getFlash()
                );
            }
            return $this->view->render( $response, "Home/login.html.twig", $with_error);
        }else{
            $model = $this->client->model;
            $group = $model->getBelongTo("id_grp.ref_grp");
            return $response->withRedirect("/{$group}/main");
        }
    }

    private function _getFlash()
    {
        $script = array(
            "script" => implode(" ",array(
                "if($.amaran){",
                    "$.amaran({",
                        "theme: 'awesome error',",
                        "content: {",
                            "title: '{$this->translator->trans("messages.title_error_login")}',",
                            "message: '{$this->translator->trans("messages.msg_error_login")}',",
                            "info: '',",
                            "icon: 'fa fa-ban'",
                        "},",
                        "position: 'bottom right',",
                        "inEffect: 'slideBottom',",
                        "outEffect: 'slideBottom'",
                    "});",
                "}"
            ))
        );
        return $script;
    }
}
