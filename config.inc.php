<?php

return array_replace_recursive([
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
            'path'       => __DIR__ . '/',
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
], ($f = __DIR__ . '/config.user.php') && is_file($f) ? require_once $f : []);