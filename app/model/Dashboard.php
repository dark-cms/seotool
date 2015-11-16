<?php

namespace app\model;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Dashboard
{

    private $request;
    private $response;
    private $args;
    private $db;

    public function __construct(Request $request, Response $response, $args, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->args     = $args;
        $this->db       = $db;

    }

}
