<?php

namespace App\View;

class Dashboard
{

    private $request;
    private $response;
    private $args;
    private $renderer;
    private $model;

    public function __construct($request, $response, $args, $renderer, $model)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;
        $this->renderer = $renderer;
        $this->model    = $model;

    }

}
