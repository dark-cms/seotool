<?php

error_reporting(0);
ini_set('display_errors', 'Off');

require __DIR__ . '/vendor/autoload.php';

$settings = require __DIR__ . '/app/settings.php';
session_start();

$app = new \Slim\App($settings);

require __DIR__ . '/app/dependencies.php';

require __DIR__ . '/app/middleware.php';

require __DIR__ . '/app/routes.php';
require __DIR__ . '/app/ajax.php';

$app->run();
