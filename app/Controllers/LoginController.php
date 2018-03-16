<?php

namespace App\Controllers;

use App\Statics\Models as STATICS;

class LoginController extends \Core\Controller
{
    public function __invoke($request, $response, $args)
    {
        $data = array("favicon" => "/./assets/images/favicon32.png");
        if($request->getAttribute("logged")!=true){
            if($request->getAttribute("error_login")==true){
                $this->logger->debug(self::class,array("error_login"=>$request->getAttribute("error_message")));
                $data = array_merge( $data, $this->_error("messages.title_error_login",$request->getAttribute("error_message")));
            }
        }else{
            $user = $this->client->model;
            $type = $user->getBelongTo("id_user.id_utype");
            if($type!=STATICS::USER_TYPE_OTHER){
                $path = "/" . $user->getBelongTo("id_grp.ref_grp")
                    . "/" . ( $type!=STATICS::USER_TYPE_SYNDIC ? 
                    $user->getBelongTo("id_user.id_utype.ref_utype") : "main" );

                $this->logger->debug(self::class,array("success_login"=>$user->log));

                return $response->withRedirect($path);

            }else{
                $this->client->logout();
                $data = array_merge( $data, $this->_error("messages.title_login_unconf","messages.msg_login_unconf"));
                $this->logger->debug(self::class,array("error_login"=>"unconfigured_client_account"));
            }
        }
        return $this->view->render( $response, "Home/login.html.twig", $data);
    }

    private function _error($title,$message)
    {
        return array(
            "flash" => $this->_getFlash( "error", $this->translator->trans($title), $this->translator->trans($message))
        );
    }

    private function _getFlash($type="ok",$title="",$msg="",$info="")
    {
        return array(
            "script" => implode(" ",array(
                "if($.jo.flash){",
                    "$.jo.flash( '{$type}', '{$title}', '{$msg}', '{$info}');",
                "}"
            ))
        );
    }
}
