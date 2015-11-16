<?php

error_reporting(999999999999999);
ini_set('display_errors', 'On');


require __DIR__ . '/vendor/autoload.php';



$settings = require __DIR__ . '/app/settings.php';

session_start();

$app = new \Slim\App($settings);

require __DIR__ . '/app/dependencies.php';

require __DIR__ . '/app/middleware.php';

require __DIR__ . '/app/routes.php';

$app->run();
