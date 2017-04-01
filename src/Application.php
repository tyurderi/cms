<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require_once __DIR__ . '/../vendor/autoload.php';
$app    = Favez\Mvc\App::instance([
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
            'shem' => 'vuex_cms',
            'user' => 'root',
            'pass' => 'vagrant'
        ],
        'app' => [
            'path'       => __DIR__ . '/../',
            'cache_path' => 'cache/'
        ],
        'debug'  => true,
        'plugin' => [
            'path' => 'ext/'
        ]
    ],
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$app->setLoader($loader);
$app::di()->registerShared('auth', function() { return new \CMS\Components\Auth(); });

$app->any('/api/[{controller}[/{action}]]', 'api:{controller}:{action}');

return $app;