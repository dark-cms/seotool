<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Login
{

    private $request;
    private $response;
    private $renderer;

    public function __construct(Request $request, Response $response, Renderer $renderer)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->renderer = $renderer;

    }

    public function showLogin()
    {

        new \App\View\Login($this->request, $this->response, $this->renderer);

    }

}
