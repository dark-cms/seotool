<?php

namespace app\controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;
use MysqliDb as DB;

class Dashboard
{

    private $model;

    public function __construct(Request $request, Response $response, DB $db, Renderer $renderer, $args)
    {
        $this->model = new \App\Model\Dashboard($request, $response, $args, $db);

        new \App\View\Dashboard($request, $response, $args, $renderer, $this->model);

    }

}
