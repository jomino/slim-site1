<?php

/*
 *
 * @dev jomino2017
 * 
 */

namespace Core;

class Dependencies
{
    public function __construct($app)
    {
        $container = $app->getContainer();

        $container['view'] = function ($container) {
            $twig = new \Slim\Views\Twig($container->settings['view']['path'], $container->settings['view']['twig']);
            $twig->addExtension(new \Slim\Views\TwigExtension($container->router, $container->request->getUri()));
            $twig->addExtension(new \Util\TranslatorExtension($container->get('translator')));
            $twig->addExtension($container->get('language'));
            $twig->addExtension($container->get('assets'));
            $twig->addExtension(new \Twig_Extension_Debug());
            return $twig;
        };

        $container['logger'] = function ($container) {
            $logger = new \Monolog\Logger($container->settings['logger']['name']);
            $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
            $logger->pushHandler(new \Monolog\Handler\StreamHandler($container->settings['logger']['path'], $container->settings['logger']['level']));
            return $logger;
        };

        $container['translator'] = function ($container) use($app){
            $loader = new \Illuminate\Translation\FileLoader(new \Illuminate\Filesystem\Filesystem(), $container->settings['view']['translations']);
            $translator = new \Illuminate\Translation\Translator($loader, $app->language);
            return $translator;
        };

        $container['assets'] = function ($container) {
            $assets = new \Util\AssetsExtension(
                $container->settings['assets']['abs-path'],
                $container->settings['assets']['rel-path'],
                $container->settings['assets']['minified']
            );
            return $assets;
        };

        $container['language'] = function ($container) use($app){
            $language = new \Util\LanguageExtension($app->language);
            return $language;
        };

        $container['client'] = function ($container) {
            $client = new \App\Auth\Auth();
            return $client->user();
        };

    }
}
