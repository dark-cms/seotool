<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;
use MysqliDb as DB;

class Keywords
{

    private $request;
    private $response;
    private $db;
    private $renderer;
    private $getParams;

    public function __construct(Request $request, Response $response, DB $db, Renderer $renderer)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;
        $this->renderer = $renderer;

        $this->getParams = $this->request->getQueryParams();

    }

    public function chart($keywordID)
    {
        $model            = new \App\Model\Keywords($this->request, $this->response, $this->db);
        $model->setProjectData();
        $keywordAvailable = $model->setKeywordData($keywordID);

        if($keywordAvailable == 0) {
            // return $this->response->withStatus(301)->withHeader("Location","/dashboard/index");
            // $this->response will hier nicht... innerhalb der route problemlos, aber eben nicht hier!
            // Alternativvorschläge?
            header("Location: /dashboard/index");
            exit();
        }

        $model->setChartInterval($this->getParams);
        $model->generateChartData();

        $view = new \App\View\Keywords($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->generateSingleChartForKeyword();
        $view->generateIntervalPicker($keywordID);
        $view->setCustomFooter();
        $view->create('chart');

    }

    public function chances()
    {
        $model = new \App\Model\Keywords($this->request, $this->response, $this->db);
        $model->setSelectDate($this->getParams);
        $model->generateDaySelectList(7);
        $model->setProjectData();
        $model->getAllCompetitors();
        $model->deleteSelectedCompetitorFromList();
        $model->generateChancesQuery();
        $model->sendQueryToDB();

        $view = new \App\View\Keywords($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->generateChancesTable();
        $view->addDateSelect('/keywords/chances/');
        $view->create('chances');

    }

    public function competition()
    {
        $model = new \App\Model\Keywords($this->request, $this->response, $this->db);
        $model->setSelectDate($this->getParams);
        $model->generateDaySelectList(7);
        $model->setProjectData();
        $model->getAllCompetitors();
        $model->generateQueryForCurrentProjectWithCompetition();
        $model->sendQueryToDB();


        $view = new \App\View\Keywords($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->generateTableHeaderWithCompetiton();
        $view->generateRankingTableWithCompetition();

        $view->addDateSelect('/keywords/competition/');
        $view->create('competition');

    }

    public function index()
    {
        $model = new \App\Model\Keywords($this->request, $this->response, $this->db);
        $model->setProjectData();
        $model->generateQueryForCurrentProject();
        $model->sendQueryToDB();

        $view = new \App\View\Keywords($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->generateRankingTable();
        $view->create('index');

    }

    public function add()
    {
        $model = new \App\Model\Keywords($this->request, $this->response, $this->db);
        $model->setProjectData();

        $view = new \App\View\Keywords($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->setInformation();
        $view->create('add');

    }

    public function __call($name, $arguments)
    {

        echo 'Methode "' . $name . '" via "' . __METHOD__ . '" wurde nicht implementiert.';

    }

}
