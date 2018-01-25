<?php

namespace App\Controllers;

class LoginController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {
        //var_dump($request->getAttribute("logged"));
        $datas = array("favicon" => "/./assets/images/favicon32.png");
        if($request->getAttribute("logged")!=true){
            if($request->getAttribute("error_login")==true){
                $datas = array_merge( $datas, array(
                    "flash" => $this->_getFlash("error",$this->translator->trans("messages.title_error_login"),$this->translator->trans("messages.msg_error_login"))
                ));
            }
            return $this->view->render( $response, "Home/login.html.twig", $datas);
        }else{
            $model = $this->client->model;
            $group = $model->getBelongTo("id_grp.ref_grp");
            return $response->withRedirect("/{$group}/main");
        }
    }

    private function _getFlash($type="ok",$title="",$msg="",$info="")
    {
        $script = array(
            "script" => implode(" ",array(
                "if($.jo.flash){",
                    "$.jo.flash( '{$type}', '{$title}', '{$msg}', '{$info}');",
                "}"
            ))
        );
        return $script;
    }
}
