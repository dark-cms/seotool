<?php

return [
    'settings' => [
        // DB settings
        'database' => [
            'host'     => 'localhost',
            'username' => 'c1_tool',
            'password' => '',
            'db'       => 'c1_tool',
            'port'     => 3306,
            'prefix'   => 'st_',
            'charset'  => 'utf8',
        ],
        // View settings
        'view'     => [
            'path' => __DIR__ . '/templates/'
        ],
        // Caching setting
        'cache'    => [
            'path'           => __DIR__ . '/../cache',
            'cacheActive'    => false,
            'compressActive' => false,
            'lifetime'       => 1,
            'debug'          => true,
            'whitelist'      => []
        ],
        // Slim Settings
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ],
];
