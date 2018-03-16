<?php

namespace App\Auth;

use App\Auth\Auth as Auth;

class AuthMiddelware
{
    
    private $context;
    
    public function __construct($context)
    {
        $this->context = $context;
    }

    public function __invoke($request, $response, $next)
    {

        $auth = new Auth();
        $logged = false;
        $error = false;
        $message = "";

        $logger = $this->context->getContainer()->logger;

        $path = $request->getUri()->getPath();

        if(is_null($auth->user())){
            if(preg_match( "#.*/(?:login)?$#", $path )){
                
                $req_log = $request->getParsedBodyParam("email",null);
                $req_pwd = $request->getParsedBodyParam("password",null);
                
                if(!empty($req_log) && !empty($req_pwd)){

                    $user = $auth->login( $req_log, md5($req_pwd));
                    if(!empty($user)){
                        $logged = true;
                        //var_dump($user);
                    }else{
                        $error = true;
                        $message = "messages.error_log_or_pwd";
                    }

                }
            }
        }else{
            if(preg_match( "#.*/logout#", $path )){
                $logger->debug( self::class, array("ask_for_logout" => $auth->user()->getUsername()));
                $auth->logout();
            }else{
                $logged = true;
            }
        }

        $request = $request->withAttribute("logged",$logged);
        $request = $request->withAttribute("error_login",$error);
        $request = $request->withAttribute("error_message",$message);

        return $next($request, $response);
    }

}