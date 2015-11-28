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

// if 404 redirect
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(301)
            ->withHeader('Location', '/dashboard/index/');
    };
};