<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;
use MysqliDb as DB;

class Settings
{

    private $request;
    private $response;
    private $db;
    private $renderer;

    public function __construct(Request $request, Response $response, DB $db, Renderer $renderer)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;
        $this->renderer = $renderer;

    }

    public function index() {
        $model = new \App\Model\Settings($this->request, $this->response, $this->db);
        $model->setProjectData();
        $model->getSettings();
        $model->getAllProjects();
        
        
        $view =new \App\View\Settings($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->loadSettings();
        $view->create();
    }

}
