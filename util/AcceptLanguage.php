<?php

namespace Util;

class AcceptLanguage
{

    private $default_language = "en";

    public function __construct($context)
    {
        $this->app = $context;
    }

    public function __invoke($request, $response, $next){
        $languageHeader = $request->getHeader('Accept-Language');
        $this->app->language = substr($languageHeader[0],0,2)?:$this->default_language;
        return $next($request, $response);
    }

}