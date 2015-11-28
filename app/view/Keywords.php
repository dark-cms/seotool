<?php

namespace App\View;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer as Renderer;

class Keywords
{

    private $request;
    private $response;
    private $renderer;
    private $modelData;
    private $footerFolder = '';

    public function __construct(Request $request, Response $response, Renderer $renderer, array $modelData)
    {

        $this->request   = $request;
        $this->response  = $response;
        $this->renderer  = $renderer;
        $this->modelData = $modelData;

        $this->viewData['title'] = $this->modelData['title'];

        $this->viewData['currentProjectNameSet'] = $this->modelData['currentProjectNameData']->getCurrentProjectName();

        $this->viewData['projectList'] = $this->modelData['projectListData']->getProjectList();

        if(isset($this->modelData['selectedDate'])) {
            $this->viewData['selectedDate'] = $this->modelData['selectedDate'];
        }

    }

    public function generateIntervalPicker($keywordID)
    {
        $html = [];

        $intervals = [
            1,
            2,
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
            $html[] = '<option ' . $this->isSelected($interval) . ' value="/keywords/chart/' . $keywordID . '/?interval=' . $interval . '">Intervall: die letzten ' . $interval . ' Tage</option>';
        }

        $this->viewData['datePicker'] = implode("\n", $html);

    }

    private function isSelected($value)
    {

        if($value == $this->modelData['chartInterval']) {
            return 'selected';
        }

    }

    public function generateSingleChartForKeyword()
    {

        $this->viewData['keywordName'] = $this->modelData['keywordName']['keywordName'];
        $lineChart                     = new \App\LineChart();
        $lineChart->setConfig([
            'element'         => 'keyword-chart',
            'xkey'            => 'd',
            'ykeys'           => "['avg']",
            'labels'          => "['Position']",
            'smooth'          => 'false',
            'ymin'            => 110,
            'ymax'            => 0,
            'resize'          => 'true',
            'continuousLine'  => 'true',
            'goals'           => '[0, 25]',
            'goalStrokeWidth' => '1',
            'goalLineColors'  => "['#d9534f']",
            'grid'            => 'true'
        ]);

        $lineChart->setDataString(
                $this->prepareRankingDataForLineChart()
        );

        $this->viewData['rankingLineJSData'] = $lineChart->generate();

    }

    public function setCustomFooter()
    {
        $this->footerFolder = 'keywords/';

    }

    private function prepareRankingDataForLineChart()
    {
        $html = [];

        foreach ($this->modelData['rankingData'] as $rankingKey => $rankingArray) {
            $value = $rankingArray['rankingPosition'];
            if(is_null($value)) {
                $value = 'null';
            }
            $html[] = "{
                        d: '" . $rankingArray['rankingAddedDay'] . "',
                        avg: " . $value . "
                   }";
        }



        return implode(',', $html);

    }

    public function generateChancesTable()
    {

        $html = [];

        foreach ($this->modelData['rankingData'] as $rankingKey => $rankingValue) {


            $html[] = '<tr>';
            $html[] = '<td>' . $rankingValue['keywordID'] . '</td>';
            $html[] = '<td class="keywordname"><strong>' . $rankingValue['keywordName'] . '</strong></td>';
            $html[] = $this->markChancesBestWorst($rankingValue);
            $html[] = '<td>' . $rankingValue['rankingURL'] . '</td>';
            $html[] = '</tr>';
        }

        $this->viewData['rankingTable'] = implode("\n", $html);

    }

    private function markChancesBestWorst($rankingValues)
    {
        $css = [
            0 => '',
            1 => '',
            2 => '',
        ];

        $rankingValues['rankingPosition0'] = $rankingValues['rankingPosition'];
        $rankingValues['bestComp0']        = $rankingValues['bestComp'];
        $rankingValues['worstComp0']       = $rankingValues['worstComp'];

        if(is_null($rankingValues['rankingPosition']))
            $rankingValues['rankingPosition0'] = 200;
        if(is_null($rankingValues['bestComp']))
            $rankingValues['bestComp0']        = 200;
        if(is_null($rankingValues['worstComp']))
            $rankingValues['worstComp0']       = 200;

        $positions = [
            0 => $rankingValues['rankingPosition0'],
            1 => $rankingValues['bestComp0'],
            2 => $rankingValues['worstComp0'],
        ];

        $keyOfMin = array_keys($positions, min($positions))[0];


        if($positions[$keyOfMin] < 200) {
            $css[$keyOfMin] = 'class="bestPos"';
        }


        $html = [];

        $html[] = '<td ' . $css[0] . '>' . $rankingValues['rankingPosition'] . '</td>';
        $html[] = '<td ' . $css[1] . '>' . $rankingValues['bestComp'] . '</td>';
        $html[] = '<td ' . $css[2] . '>' . $rankingValues['worstComp'] . '</td>';

        return implode("\n", $html);

    }

    public function addDateSelect($url = '/keywords/competition/')
    {
        $html   = [];
        $html[] = '<div class="form-group">';
        $html[] = '<select id="dateSelecter" class="form-control">';


        foreach ($this->viewData['selectedDate'] as $dVal => $dDate) {
            $selected = '';
            if(isset($dDate['selected'])) {
                $selected                       = 'selected';
                $this->viewData['selectedDate'] = $dDate['date'];
            }

            $html[] = '<option ' . $selected . ' value="' . $url . '?date=' . $dDate['date'] . '">Daten für den ' . $dDate['date'] . '</option>';
        }

        $html[] = '</select>';
        $html[] = '</div>';

        $this->viewData['selectedDateHTML'] = implode("\n", $html);

    }

    public function setInformation()
    {
        if(isset($this->modelData['projectData']['currentProjectID']) && $this->modelData['projectData']['currentProjectID'] > 0) {
            $this->viewData['addInformation'] = 'Du bist aktuell dabei für das <strong>Hauptprojekt</strong> (inklusive aller Konkurrenten) neue Keywords anzulegen. Ein nachträglcihes Ändern der Keywords ist nicht möglich, prüfe daher, ob alles richtig geschrieben wurde. Die Keywords kannst du einfach untereinander reinschreiben und abspeichern.';
        }
        else {
            $this->viewData['addInformation'] = '<strong>Achtung:</strong> Offenbar hast du noch kein Projekt als AKTIV gesetzt. Das Tool wüsste nicht, welchem Hauptprojekt es die neuen Keywords zuordnen soll. Bitte wähl oben rechts eines der Projekte und lade die Seite neu!';
        }

    }

    public function generateTableHeaderWithCompetiton()
    {

        $html = [];

        foreach ($this->modelData['competitorList'] as $compKey => $compArray) {
            $html[] = '<th>' . \App\Tool::removeSchemeFromURL($compArray['projectURL']) . '</th>';
        }

        $this->viewData['tblHeaderCompetiton'] = implode("\n", $html);

    }

    public function generateRankingTableWithCompetition()
    {
        $html = [];

        foreach ($this->modelData['rankingData'] as $rankingKey => $rankingValue) {


            $html[] = '<tr>';
            $html[] = '<td>' . $rankingValue['keywordID'] . '</td>';
            $html[] = '<td class="keywordname"><strong>' . $rankingValue['keywordName'] . '</strong></td>';

            $html[] = $this->markBestCompetitor($rankingValue);

            $html[] = '</tr>';
        }

        $this->viewData['rankingTable'] = implode("\n", $html);

    }

    private function markBestCompetitor($rankingValue)
    {

        $html      = [];
        $css       = [];
        $positions = [];

        $competitorIterator = 0;
        $cssCounter         = 0;
        $competitiorCount   = count($rankingValue) - 2; //first two are id and name
        $competitiorCount   = $competitiorCount / 2; //because every second column is an url



        while ($cssCounter < $competitiorCount) {

            $css[$cssCounter]                         = '';
            $rankingValue['comp' . $cssCounter . '1'] = $rankingValue['comp' . $cssCounter];


            if(is_null($rankingValue['comp' . $cssCounter]))
                $rankingValue['comp' . $cssCounter . '1'] = 200;


            $positions[$cssCounter] = $rankingValue['comp' . $cssCounter . '1'];

            $cssCounter++;
        }


        $keyOfMin = array_keys($positions, min($positions))[0];

        if($positions[$keyOfMin] < 200) {
            $css[$keyOfMin] = 'class="bestPos"';
        }


        while ($competitorIterator < $competitiorCount) {

            $html[] = '<td ' . $css[$competitorIterator] . '><a class="urlInTbl" href="' . $rankingValue['url' . $competitorIterator] . '" target="_blank">' . $rankingValue['comp' . $competitorIterator] . '</a></td>';
            $competitorIterator++;
        }

        return implode("\n", $html);

    }

    public function generateRankingTable()
    {
        $html = [];

        foreach ($this->modelData['rankingData'] as $rankingKey => $rankingValue) {
            $html[] = '<tr class="k' . $rankingValue['keywordID'] . '">';
            $html[] = '<td>' . $rankingValue['keywordID'] . '</td>';
            $html[] = '<td class="keywordname"><strong>' . $rankingValue['keywordName'] . '</strong></td>';
            $html[] = '<td>' . $rankingValue['updated'] . '</td>';
            $html[] = '<td class="tblBtn"><a href="/keywords/chart/' . $rankingValue['keywordID'] . '/"><i class="fa fa-area-chart"></i></a></td>';
            //$html[] = '<td class="tblBtn"><i data-kid="18" class="fa fa-comment"></i></td>';
            $html[] = '<td>' . $rankingValue['bestPos'] . '</td>';
            $html[] = $this->markBestPositionsMakeHTML($rankingValue);
            //$html[] = '<td class="tblBtn"><a href="#"><i class="fa fa-edit keywordEdit"></i></a></td>';
            $html[] = '<td class="tblBtn"><i data-kid="' . $rankingValue['keywordID'] . '" class="fa fa-remove remove keywordRemove"></i></td>';
            $html[] = '</tr>';
        }

        $this->viewData['rankingTable'] = implode("\n", $html);

    }

    private function markBestPositionsMakeHTML($rankingValue)
    {

        $css = [
            0 => '',
            1 => '',
            2 => '',
            3 => '',
            4 => '',
        ];

        $rankingValue['tag01'] = $rankingValue['tag0'];
        $rankingValue['tag11'] = $rankingValue['tag1'];
        $rankingValue['tag21'] = $rankingValue['tag2'];
        $rankingValue['tag31'] = $rankingValue['tag3'];
        $rankingValue['tag41'] = $rankingValue['tag4'];

        if(is_null($rankingValue['tag0']))
            $rankingValue['tag01'] = 200;
        if(is_null($rankingValue['tag1']))
            $rankingValue['tag11'] = 200;
        if(is_null($rankingValue['tag2']))
            $rankingValue['tag21'] = 200;
        if(is_null($rankingValue['tag3']))
            $rankingValue['tag31'] = 200;
        if(is_null($rankingValue['tag4']))
            $rankingValue['tag41'] = 200;

        $positions = [
            0 => $rankingValue['tag01'],
            1 => $rankingValue['tag11'],
            2 => $rankingValue['tag21'],
            3 => $rankingValue['tag31'],
            4 => $rankingValue['tag41']
        ];

        $keyOfMin = array_keys($positions, min($positions))[0];

        if($positions[$keyOfMin] < 200) {
            $css[$keyOfMin] = 'class="bestPos"';
        }


        $html[] = '<td ' . $css[0] . '><a class="urlInTbl" href="' . $rankingValue['url0'] . '" target="_blank">' . $rankingValue['tag0'] . '</a></td>';
        $html[] = $this->markDeltainColor($rankingValue['delta01']);
        $html[] = '<td ' . $css[1] . '><a class="urlInTbl" href="' . $rankingValue['url1'] . '" target="_blank">' . $rankingValue['tag1'] . '</a></td>';
        $html[] = $this->markDeltainColor($rankingValue['delta12']);
        $html[] = '<td ' . $css[2] . '><a class="urlInTbl" href="' . $rankingValue['url2'] . '" target="_blank">' . $rankingValue['tag2'] . '</a></td>';
        $html[] = $this->markDeltainColor($rankingValue['delta23']);
        $html[] = '<td ' . $css[3] . '><a class="urlInTbl" href="' . $rankingValue['url3'] . '" target="_blank">' . $rankingValue['tag3'] . '</a></td>';
        $html[] = $this->markDeltainColor($rankingValue['delta34']);
        $html[] = '<td ' . $css[4] . '><a class="urlInTbl" href="' . $rankingValue['url4'] . '" target="_blank">' . $rankingValue['tag4'] . '</a></td>';

        return implode("\n", $html);

    }

    private function markDeltainColor($deltaVal)
    {
        $pre = '';
        if($deltaVal > 0) {
            $css = 'class="pos"';
            $pre = '+';
        }
        if($deltaVal < 0) {
            $css = 'class="neg"';
        }
        if(is_null($deltaVal)) {
            $css = '';
        }
        return '<td ' . $css . '>' . $pre . $deltaVal . '</td>';

    }

    public function loadProjectData()
    {
        $this->viewData['projectData'] = $this->modelData['projectData'];

        $this->viewData['projectData']['currentProjectURL'] = \App\Tool::removeSchemeFromURL($this->viewData['projectData']['currentProjectURL']);

    }

    public function create($template = 'index')
    {
        $this->renderer->render($this->response, 'header.php', $this->viewData);
        $this->renderer->render($this->response, 'navigation.php', $this->viewData);
        $this->renderer->render($this->response, 'keywords/' . $template . '.php', $this->viewData);


        $this->renderer->render($this->response, $this->footerFolder . 'footer.php', $this->viewData);

    }

}
