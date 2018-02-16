<?php

namespace Core;

class Settings
{
    private $settings;

    public function __construct()
    {
        $this->settings = [
            'settings' => [
                'displayErrorDetails' => \App\Parameters::SYSTEM['debug'],
                'addContentLengthHeader' => false,
                'view' => [
                    'twig' => [
                        'debug' => \App\Parameters::SYSTEM['debug']
                    ],
                    'path' => __DIR__ . '/../app/Views',
                    'translations' => __DIR__ . '/../app/Translations'
                ],
                'logger' => [
                    'name' => 'APPLICATION',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../app/_cache/application.log',
                    'level' => (bool)\App\Parameters::SYSTEM['debug'] ? \Monolog\Logger::DEBUG : \Monolog\Logger::ERROR
                ],
                'localisation' => [
                    'path' => __DIR__ . '/../app/Models/localisation'
                ],
                'loadedfiles' => [
                    'path' => __DIR__ . '/../files'
                ],
                'assets' => [
                    'minified' => false,
                    'rel-path' => '/../assets',
                    'abs-path' => __DIR__ . '/../public/assets',
                    'buf-path' => __DIR__ . '/../app/_cache'
                ]
            ]
        ];
    }

    public function load()
    {
        return $this->settings;
    }
}