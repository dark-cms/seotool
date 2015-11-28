<?php

namespace App\View;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Summary
{

    private $request;
    private $response;
    private $renderer;
    private $modelData;
    private $viewData;
    private $compIdentifiers;

    public function __construct(Request $request, Response $response, Renderer $renderer, array $modelData)
    {

        $this->request   = $request;
        $this->response  = $response;
        $this->renderer  = $renderer;
        $this->modelData = $modelData;

        $this->viewData['title'] = $this->modelData['title'];

        $this->viewData['currentProjectNameSet'] = $this->modelData['currentProjectNameData']->getCurrentProjectName();

        $this->viewData['projectList'] = $this->modelData['projectListData']->getProjectList();

    }

    public function generateTrackedKeywordJSData()
    {
        $lineChart = new \App\LineChart();
        $lineChart->setConfig([
            'element'        => 'summary-keywords',
            'xkey'           => 'd',
            'ykeys'          => "['nr']",
            'labels'         => "['Getrackte Keywords']",
            'smooth'         => 'false',
            'resize'         => 'true',
            'continuousLine' => 'true'
        ]);

        $lineChart->setDataString(
                $this->prepareTrackedKeywordDataForLineChart()
        );

        $this->viewData['rankingLineJSData'] = $lineChart->generate();

    }

    private function prepareTrackedKeywordDataForLineChart()
    {
        $html = [];


        foreach ($this->modelData['queryresultData'] as $pointKey => $pointData) {
            $value = $pointData['nr'];
            if(is_null($value)) {
                $value = 'null';
            }
            $html[] = "{
                        d: '" . $pointData['rankingAddedDay'] . "',
                        nr: " . $value . "
                   }";
        }



        return implode(',', $html);

    }

    public function generateCompetitionRankingJSData()
    {
        $lineChart = new \App\LineChart();

        $dataString = $this->prepareCompetitionRankingDataForLineChart();

        $lineChart->setConfig([
            'element'         => 'summary-competition',
            'xkey'            => 'd',
            'xLabels'         => 'day',
            'ykeys'           => "['comp" . implode("','comp", $this->compIdentifiers) . "']",
            'labels'          => "[" . $this->competitionNames() . "]",
            'smooth'          => 'false',
            'resize'          => 'true',
            'continuousLine'  => 'true',
            'ymin'            => 100,
            'ymax'            => 0,
            'goals'           => '[0, 25]',
            'goalStrokeWidth' => '1',
            'goalLineColors'  => "['#d9534f']",
            'grid'            => 'true'
        ]);

        $lineChart->setDataString(
                $dataString
        );

        $this->viewData['rankingLineJSData'] = $lineChart->generate();

    }

    private function competitionNames()
    {
        $html = [];

        foreach ($this->modelData['projectData']['competitorList'] as $compDefaultKey => $compData) {

            foreach ($this->compIdentifiers as $compID) {
                if($compID == $this->modelData['projectData']['competitorList'][$compDefaultKey]['projectID']) {
                    $html[] = "'" . \App\Tool::removeSchemeFromURL($this->modelData['projectData']['competitorList'][$compDefaultKey]['projectURL']) . "'";
                }
            }
        }

        return implode(',', $html);

    }

    private function prepareCompetitionRankingDataForLineChart()
    {
        $this->compIdentifiers = [];
        
        $eachDataAllProjects = [];
        foreach ($this->modelData['queryresultData'] as $compID => $compData) {
            $iteratorPos = 1;
            
            
            $this->compIdentifiers['comp' . $compID] = $compID;

            while ($iteratorPos <= $this->modelData['timeData']['interval']) {
                $theDate                                         = $compData['d' . $iteratorPos];
                $eachDataAllProjects[$theDate]['comp' . $compID] = $compData['r' . $iteratorPos];
                $iteratorPos++;
            }
        }

        $html   = [];
        $html_d = [];

        foreach ($eachDataAllProjects as $theDate => $compValues) {

            $html_d   = [];
            $html_d[] = "d: '" . $theDate . "'";

            foreach ($this->compIdentifiers as $theProjectID) {

                $dataPoint = $compValues['comp' . $theProjectID];
                if(is_null($dataPoint) || $dataPoint >= 100) {
                    $dataPoint = 'null';
                }
                $html_d[] = "comp" . $theProjectID . ': ' . $dataPoint;
            }

            $html[] = '{' . implode(",", $html_d) . '}';
        }


        return implode(',', $html);

    }

    public function generateDatePicker($action = 'ranking', $add = 0)
    {
        $html = [];

        $intervals = [
            3,
            7,
            14,
            30,
            60,
            90,
            120,
            180,
            240,
            360,
            720
        ];

        foreach ($intervals as $interval) {
            $html[] = '<option ' . $this->isSelected($interval) . ' value="/summary/' . $action . '/?last=' . $interval . '">Intervall: die letzten ' . $interval . ' Tage</option>';
        }

        $this->viewData['datePicker'] = implode("\n", $html);

    }

    private function isSelected($value)
    {

        if($value == $this->modelData['timeData']['interval']) {
            return 'selected';
        }

    }

    public function generateSingleRankingJSData()
    {

        $lineChart = new \App\LineChart();
        $lineChart->setConfig([
            'element'         => 'summary-ranking',
            'xkey'            => 'd',
            'ykeys'           => "['avg']",
            'labels'          => "['Ranking im Durchschnitt']",
            'smooth'          => 'false',
            'resize'          => 'true',
            'ymin'            => 100,
            'ymax'            => 0,
            'continuousLine'  => 'true',
            'goals'           => '[0, 25]',
            'goalStrokeWidth' => '1',
            'goalLineColors'  => "['#d9534f']",
            'grid'            => 'true'
        ]);

        $lineChart->setDataString(
                $this->prepareSingleRankingDataForLineChart()
        );

        $this->viewData['rankingLineJSData'] = $lineChart->generate();

    }

    public function loadProjectData()
    {
        $this->viewData['projectData']                      = $this->modelData['projectData'];
        $this->viewData['projectData']['currentProjectURL'] = \App\Tool::removeSchemeFromURL($this->viewData['projectData']['currentProjectURL']);

    }

    private function prepareSingleRankingDataForLineChart()
    {
        $html = [];

        $iteratorPos = 1;

        $days = (count($this->modelData['queryresultData'][0]) - 1) / 2;

        while ($iteratorPos <= $days) {

            $value = $this->modelData['queryresultData'][0]['r' . $iteratorPos];
            if(is_null($value)) {
                $value = 'null';
            }
            $html[] = "{
                        d: '" . $this->modelData['queryresultData'][0]['d' . $iteratorPos] . "',
                        avg: " . $value . "
                   }";
            $iteratorPos++;
        }


        return implode(',', $html);

    }

    public function create($template = 'ranking')
    {
        $this->renderer->render($this->response, 'header.php', $this->viewData);
        $this->renderer->render($this->response, 'navigation.php', $this->viewData);
        $this->renderer->render($this->response, 'summary/' . $template . '.php', $this->viewData);
        $this->renderer->render($this->response, 'summary/footer.php', $this->viewData);

    }

}
