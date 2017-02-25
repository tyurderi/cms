<?php

error_reporting(E_ALL);
ini_set("display_errors", "on");

require_once __DIR__ . '/vendor/autoload.php';

$app = Favez\Mvc\App::instance([
    'config' => [
        'modules' => [
            'api' => [
                'controller' => [
                    'namespace'     => 'CMS\\Controllers\\Api\\',
                    'class_suffix'  => 'Controller',
                    'method_suffix' => 'Action'
                ]
            ]
        ],
        'view' => [
            'theme_path' => 'themes/',
            'cache_path' => 'cache/twig/'
        ],
        'database' => [
            'host' => 'localhost',
            'shem' => 'test',
            'user' => 'root',
            'pass' => ''
        ],
        'app' => [
            'path'       => __DIR__ . '/',
            'cache_path' => 'cache/'
        ],
        'debug' => true
    ],
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$app->any('/api/[{controller}[/{action}]]', 'api:{controller}:{action}');

$app->run();