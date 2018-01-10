<?php

namespace Core;

class Framework
{
    public function __construct($app)
    {
        $container = $app->getContainer();
                
        // framework configuration
        $configuration = new \Framework\Configuration(array(
            "type" => "ini",
            "options" => array("application" => __DIR__ . "/../app/Configuration")
        ));
        \Framework\Registry::set("configuration", $configuration->initialize());
        
        // database
        $database = new \Framework\Database();
        \Framework\Registry::set("database", $database->initialize());
            
        // database texts
        $localisation = new \Framework\Localisation(array(
            "defaultPath" => $container->settings['localisation']['path']
        ));
        \Framework\Registry::set("localisation", $localisation->initialize(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2)));

        // unset locals
        unset($configuration);
        unset($database);
        unset($localisation);

    }
    
    public function __invoke($request, $response, $next){
        return $next($request, $response);
    }

}