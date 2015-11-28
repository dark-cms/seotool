<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;
use MysqliDb as DB;

class Projects
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

    public function edit( $projectID ) {
        $model = new \App\Model\Projects($this->request, $this->response, $this->db);
        $model->loadEditPageData();
        $model->loadProjectsToEditData($projectID);
        
        $view = new \App\View\Projects($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->generateMainProjectArea();
        $view->generateCompetitionArea();
        $view->create('edit'); 
    }
    
    public function index()
    {

        $model = new \App\Model\Projects($this->request, $this->response, $this->db);
        $model->loadIndexPageData();
        $model->loadProjects();

        $view = new \App\View\Projects($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->generateList();
        $view->create('index');

    }

    public function add()
    {

        $model = new \App\Model\Projects($this->request, $this->response, $this->db);
        $model->loadAddPageData();

        $view = new \App\View\Projects($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->create('add');

    }

}
