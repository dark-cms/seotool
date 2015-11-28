
<?php

$app->group('/ajax', function () {

    $this->post('/{class:projects|keywords|settings|backlinks}/{action:add|remove|update}/', function ($request, $response, $args) {

        $className = '\\App\\Ajax\\' . ucfirst($args['class']);

        $c = new $className($request, $response, $this->db);
        $c->$args['action']();
    });
})->add(new App\CheckAuth());
