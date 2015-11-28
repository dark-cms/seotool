<?php

namespace App\View;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Dashboard
{

    private $request;
    private $response;
    private $renderer;
    private $modelData;
    private $hasDonutChart = 0;

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

    public function createDonutChartJS()
    {


        $this->hasDonutChart = 1;

        $donutChart = new \App\DonutChart();
        $donutChart->setConfig([
            'element' => 'dashboard-relations'
        ]);

        $donutChart->setDataString(
                $this->prepareRelationDataForDonutChart()
        );

        $this->viewData['relationDonutJSData'] = $donutChart->generate();

    }

    private function prepareRelationDataForDonutChart()
    {
        $package = [];

        if(!empty($this->modelData['backlinkRelations'])) {
            foreach ($this->modelData['backlinkRelations'] as $donutData) {
                $package[] = '{label: "' . $donutData['backlinkRelation'] . '",value: ' . $donutData['links'] . '}';
            }
        }
        else {
            $package[] = '{label: "Links",value: 0}';
        }


        return implode(',', $package);

    }

    public function createLineChartJSData()
    {


        $lineChart = new \App\LineChart();
        $lineChart->setConfig([
            'element'        => 'dashboard-ranking',
            'xkey'           => 'd',
            'ykeys'          => "['avg']",
            'labels'         => "['Ranking im Durchschnitt']",
            'smooth'         => 'false',
            'ymin'           => 100,
            'ymax'           => 0,
            'resize'         => 'true',
            'continuousLine' => 'true'
        ]);

        $lineChart->setDataString(
                $this->prepareRankingDataForLineChart()
        );

        $this->viewData['rankingLineJSData'] = $lineChart->generate();

    }

    private function prepareRankingDataForLineChart()
    {
        $html = [];

        $iteratorPos = 1;

        $days = count($this->modelData['lastXDayRankData'][0]) / 2;

        while ($iteratorPos <= $days - 1) {

            $value = $this->modelData['lastXDayRankData'][0]['dayIndex' . $iteratorPos];
            if(is_null($value)) {
                $value = 'null';
            }
            $html[] = "{
                        d: '" . $this->modelData['lastXDayRankData'][0]['dayNr' . $iteratorPos] . "',
                        avg: " . $value . "
                   }";
            $iteratorPos++;
        }


        return implode(',', $html);

    }

    public function createChancesTable()
    {
        $html = [];

        foreach ($this->modelData['chancesData'] as $chancesKey => $chancesValueArray) {
            $html[] = '<tr><td>' . $chancesValueArray['keywordName'] . '</td><td><a href="' . $chancesValueArray['rankingURL'] . '" target="_blank">' . $chancesValueArray['rankingPosition'] . '</a></td></tr>';
        }

        $this->viewData['chancesTable'] = implode("\n", $html);

    }

    public function createWinnerTable()
    {
        $html = [];

        foreach ($this->modelData['winnerData'] as $winnerKey => $winnerValueArray) {
            $pos = (int)$winnerValueArray['delta'];
            if($pos > 0) {

                $html[] = '<tr><td>' . $winnerValueArray['keywordName'] . '</td><td>' . $winnerValueArray['pos'] . '</td><td class="green">+' . $pos . '</td></tr>';
            }
        }

        $this->viewData['winnerTable'] = implode("\n", $html);

    }

    public function createLoserTable()
    {
        $html = [];

        foreach ($this->modelData['loserData'] as $loserKey => $loserValueArray) {
            $pos = (int)$loserValueArray['delta'];
            if($pos < 0) {

                $html[] = '<tr><td>' . $loserValueArray['keywordName'] . '</td><td>' . $loserValueArray['pos'] . '</td><td class="red">' . $pos . '</td></tr>';
            }
        }

        $this->viewData['loserTable'] = implode("\n", $html);

    }

    public function setQuickInfoDataInView()
    {
        $this->viewData['quickInfo'] = $this->modelData['quickInfoData'][0];

    }

    public function createTrackedKeywordsWarning()
    {
        $this->viewData['trackedKeywordWarning']['top']    = '';
        $this->viewData['trackedKeywordWarning']['middle'] = '';

        if($this->modelData['quickInfoData'][0]['keywords'] != $this->modelData['quickInfoData'][0]['keywordsTrackedToday']) {

            $html   = [];
            $html[] = '<div class="row">';
            $html[] = '<div class="col-lg-12">';
            $html[] = '<div class="alert alert-info alert-dismissable">';
            $html[] = '<i class="fa fa-info-circle"></i>  Vergiss nicht, dass es für HEUTE unter Umständen noch keine Ranking-Daten geben kann. Im Dashboard beziehen sich alle Angaben auf heute. Sollte alles noch auf 0 stehen, so schau einfach später vorbei. Spätestens eine Stunde nach dem letzten Cronjob-Durchlauf sollte alles da sein. Siehe hierzu in den Einstellungen.';
            $html[] = '</div>';
            $html[] = '</div>';
            $html[] = '</div>';

            $this->viewData['trackedKeywordWarning']['top'] = implode("\n", $html);


            $html   = [];
            $html[] = '<div class="row">';
            $html[] = '<div class="col-lg-12">';
            $html[] = '<div class="alert alert-info alert-dismissable">';
            $html[] = '<i class="fa fa-info-circle"></i>  Auch hier gilt - es werden Daten von heute und gestern verglichen. Unter Umständen (meist früh morgens) kann es sein, dass für heute noch keine Daten vorhanden sind und du etwas warten musst.';
            $html[] = '</div>';
            $html[] = '</div>';
            $html[] = '</div>';

            $this->viewData['trackedKeywordWarning']['middle'] = implode("\n", $html);
        }

    }

    public function loadProjectData()
    {

        $this->viewData['projectData'] = $this->modelData['projectData'];

        $this->viewData['projectData']['currentProjectURL'] = \App\Tool::removeSchemeFromURL($this->viewData['projectData']['currentProjectURL']);

    }

    public function createBarChartJSData()
    {

        $barChart = new \App\BarChart();

        $barChart->setConfig([
            'element'     => 'dashboard-posdis',
            'xkey'        => 'posData',
            'ykeys'       => "['posAnzahl']",
            'labels'      => "['Anzahl Keywords']",
            'barRatio'    => '0.4',
            'xLabelAngle' => '0',
            'hideHover'   => 'auto',
            'resize'      => 'true',
        ]);

        $barChart->setDataString(
                $this->preparePosDisDataForBarChart()
        );

        $this->viewData['positionDistributionJSData'] = $barChart->generate();

    }

    private function preparePosDisDataForBarChart()
    {
        $html = [];

        $iteratorPos = 1;


        while ($iteratorPos <= 7) {

            $html[] = "{
                        posData: '" . $this->modelData['queryResult'][0]['n' . $iteratorPos] . "',
                        posAnzahl: " . $this->modelData['queryResult'][0]['p' . $iteratorPos] . "
                   }";
            $iteratorPos++;
        }


        return implode(',', $html);

    }

    public function create()
    {

        $this->renderer->render($this->response, 'header.php', $this->viewData);
        $this->renderer->render($this->response, 'navigation.php', $this->viewData);
        $this->renderer->render($this->response, 'dashboard/index.php', $this->viewData);
        $this->renderer->render($this->response, 'dashboard/footer.php', $this->viewData);

    }

}
