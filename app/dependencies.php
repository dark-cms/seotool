<?php

$container = $app->getContainer();

// db
$container['db'] = function ($container) {
    $settings = $container->get('settings');
    return new \MysqliDb($settings['database']);
};

// view
$container['renderer'] = function($container) {
    $settings     = $container->get('settings');
    $templatePath = $settings['view']['path'];
    return new \Slim\Views\PhpRenderer($templatePath);
};

// caching
$container['\App\SlimPageCaching'] = function ($container) {
    return new \App\SlimPageCaching($container->get('settings'));
};
