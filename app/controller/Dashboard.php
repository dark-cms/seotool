<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;
use MysqliDb as DB;

class Dashboard
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

    public function index()
    {
        $model = new \App\Model\Dashboard($this->request, $this->response, $this->db);
        $model->setSelectDate($this->getParams);
        $model->setProjectData();
        $model->generatePositionDistributionQuery();
        $model->sendQueryToDB();
        $model->getQuickInfoData();
        $model->getWinners();
        $model->getLosers();
        $model->getChances();
        $model->getBacklinkChart();
        $model->getLastDaysIndex(7);

        $view = new \App\View\Dashboard($this->request, $this->response, $this->renderer, $model->getModelData());
        $view->loadProjectData();
        $view->setQuickInfoDataInView();
        $view->createTrackedKeywordsWarning();
        $view->createBarChartJSData();
        $view->createWinnerTable();
        $view->createLoserTable();
        $view->createChancesTable();
        $view->createLineChartJSData();
        $view->createDonutChartJS();
        $view->create();

    }

}
