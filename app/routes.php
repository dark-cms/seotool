
<?php

$app->get('/{controller:dashboard|stats}/{action}/', function ($request, $response, $args) {

    $controller = '\\App\\Controller\\' . ucfirst($args['controller']);

    new $controller($request, $response, $this->db, $this->renderer, $args);
});
