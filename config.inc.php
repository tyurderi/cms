<?php

return [
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
            'pass' => ''
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
];