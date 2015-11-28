<?php

define('USER', 'admin');
define('PASS', '123456');

return [
    'settings' => [
        // DB settings
        'database' => [
            'host'     => 'localhost',
            'username' => '',
            'password' => '',
            'db'       => '',
            'port'     => 3306,
            'prefix'   => 'st_',
            'charset'  => 'utf8',
        ],
        // View settings
        'view'     => [
            'path' => __DIR__ . '/templates/'
        ],
        // Slim Settings
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ],
];

