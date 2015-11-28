<?php

$app->get('/login/', function ($request, $response) {

    $session = new \RKA\Session();

    if($session->loggedin === '1') {
        return $response->withStatus(301)->withHeader('Location', '/dashboard/index/');
    }

    $c = new App\Controller\Login($request, $response, $this->renderer);
    $c->showlogin();
});

$app->post('/login/', function ($request, $response) {

    return $response->withStatus(301)->withHeader('Location', '/dashboard/index/');
})->add(new App\SimpleAuth());

$app->get('/logout/', function ($request, $response) {
    return $response->withStatus(301)->withHeader('Location', '/login/');
})->add(new App\Logout());

$app->get('/{class:dashboard|settings}/index/', function ($request, $response, $args) {

    $className  = 'App\\Controller\\' . ucfirst($args['class']);
    $controller = new $className($request, $response, $this->db, $this->renderer);
    $controller->index();
})->add(new App\CheckAuth());


$app->get('/summary/{action:ranking|competition|keywords}/', function ($request, $response, $args) {

    $controller = new App\Controller\Summary($request, $response, $this->db, $this->renderer);
    $controller->$args['action']();
})->add(new App\CheckAuth());


$app->group('/keywords', function () {
    $this->get('/{action:index|add|competition|chances}/', function ($request, $response, $args) {
        $controller = new App\Controller\Keywords($request, $response, $this->db, $this->renderer);
        $controller->$args['action']();
    });

    $this->get('/chart/{id:\d+}/', function ($request, $response, $args) {
        $controller = new App\Controller\Keywords($request, $response, $this->db, $this->renderer);
        $controller->chart($args['id']);
    });
})->add(new App\CheckAuth());


$app->group('/projects', function () {
    $this->get('/{action:index|add}/', function ($request, $response, $args) {
        $controller = new App\Controller\Projects($request, $response, $this->db, $this->renderer);
        $controller->$args['action']();
    });

    $this->get('/edit/{id:\d+}/', function ($request, $response, $args) {
        $controller = new App\Controller\Projects($request, $response, $this->db, $this->renderer);
        $controller->edit($args['id']);
    });

    $this->get('/select/{id:\d+}/', function ($request, $response, $args) {
        $session = new \RKA\Session();
        $session->set('currentProject', $args['id']);
        return $response->withStatus(301)->withHeader('Location', '/dashboard/index/');
    });
})->add(new App\CheckAuth());


$app->group('/backlinks', function () {
    $this->get('/{action:index|add}/', function ($request, $response, $args) {
        $controller = new App\Controller\Backlinks($request, $response, $this->db, $this->renderer);
        $controller->$args['action']();
    });
})->add(new App\CheckAuth());


$app->group('/notes', function () {
    $this->get('/index/', function ($request, $response) {
        $controller = new App\Controller\Notes($request, $response, $this->db, $this->renderer);
    });
})->add(new App\CheckAuth());

