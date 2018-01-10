<?php

namespace Core;

class Bootstrap
{
    public function __construct()
    {
        session_start();

        $settings = new \Core\Settings();
        $app = new \Slim\App($settings->load());
        
        new \Core\Middleware($app);

        new \Core\Dependencies($app);
        
        new \Core\Routes($app);

        $app->run();
    }
}
