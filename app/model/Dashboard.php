<?php

namespace App\Model;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Dashboard
{

    private $request;
    private $response;
    private $db;
    private $modelData      = [];
    private $additionalData = [];
    private $session;
    private $projectData    = [];
    private $query;

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;
        $this->session  = new \RKA\Session();

        $this->additionalData['title'] = 'SEO Tool: Dashboard';

        $this->additionalData['currentProjectNameData'] = new \App\CurrentProject_TopBar($this->db);

        $this->additionalData['projectListData'] = new \App\ProjectList_TopBar($this->db);

    }

    public function getBacklinkChart()
    {
        $this->db->where('b.backlinkProject', $this->projectData['currentProjectID']);
        $this->db->join('backlinkRelations br', 'br.backlinkRelationID = b.backlinkRelation', 'LEFT');
        $this->db->groupBy('b.backlinkRelation');
        $this->modelData['backlinkRelations'] = $this->db->get('backlinks b', null, 'CONCAT(br.backlinkRelation,\'-Links\') as backlinkRelation, COUNT( * ) AS links');

    }

    public function getLastDaysIndex($lastDays = 7)
    {

        $lastDaysIterator       = 0;
        $lastDaysIteratorDayVal = $lastDays;

        $query   = [];
        $query[] = 'SELECT ';

        $queryM = [];

        while ($lastDaysIterator <= ($lastDays)) {
            $day = date('Y-m-j', strtotime('-' . $lastDaysIteratorDayVal . ' day'));

            $queryM[] = "(SELECT '" . $day . "') as dayNr" . $lastDaysIterator;
            $queryM[] = "(SELECT ROUND(AVG(ifNull(rankingPosition,100)),2) FROM st_rankings WHERE projectID=" . $this->projectData['currentProjectID'] . " AND rankingAddedDay='" . $day . "') as dayIndex" . $lastDaysIterator;

            $lastDaysIterator++;
            $lastDaysIteratorDayVal--;
        }

        $query[] = implode(',', $queryM);
        $query   = implode(' ', $query);

        $this->modelData['lastXDayRankData'] = $this->db->rawQuery($query);

    }

    public function getWinners()
    {

        $query = 'SELECT r.keywordID,k.keywordName,';
        $query .= '(SELECT k3.rankingPosition FROM st_rankings k3 WHERE k3.keywordID=r.keywordID AND k3.projectID=' . $this->projectData['currentProjectID'] . ' AND k3.rankingAddedDay = \'' . $this->additionalData['currentDate'] . '\') as pos,';
        $query .= '((SELECT k3.rankingPosition FROM st_rankings k3 WHERE k3.keywordID=r.keywordID AND k3.projectID=' . $this->projectData['currentProjectID'] . ' AND k3.rankingAddedDay = \'' . $this->additionalData['yesterday'] . '\')-';
        $query .= '(SELECT k3.rankingPosition FROM st_rankings k3 WHERE k3.keywordID=r.keywordID AND k3.projectID=' . $this->projectData['currentProjectID'] . ' AND k3.rankingAddedDay = \'' . $this->additionalData['currentDate'] . '\')) as delta';
        $query .= ' FROM st_rankings r LEFT JOIN st_keywords k ON r.keywordID=k.keywordID WHERE r.projectID=' . $this->projectData['currentProjectID'] . ' AND r.rankingAddedDay=\'' . $this->additionalData['yesterday'] . '\' ORDER BY delta DESC LIMIT 15 ';

        $this->modelData['winnerData'] = $this->db->rawQuery($query);

    }

    public function getLosers()
    {

        $query = 'SELECT r.keywordID,k.keywordName,';
        $query .= '(SELECT k3.rankingPosition FROM st_rankings k3 WHERE k3.keywordID=r.keywordID AND k3.projectID=' . $this->projectData['currentProjectID'] . ' AND k3.rankingAddedDay = \'' . $this->additionalData['currentDate'] . '\') as pos,';
        $query .= '((SELECT k3.rankingPosition FROM st_rankings k3 WHERE k3.keywordID=r.keywordID AND k3.projectID=' . $this->projectData['currentProjectID'] . ' AND k3.rankingAddedDay = \'' . $this->additionalData['yesterday'] . '\')-';
        $query .= '(SELECT k3.rankingPosition FROM st_rankings k3 WHERE k3.keywordID=r.keywordID AND k3.projectID=' . $this->projectData['currentProjectID'] . ' AND k3.rankingAddedDay = \'' . $this->additionalData['currentDate'] . '\')) as delta';
        $query .= ' FROM st_rankings r LEFT JOIN st_keywords k ON r.keywordID=k.keywordID WHERE r.projectID=' . $this->projectData['currentProjectID'] . ' AND r.rankingAddedDay=\'' . $this->additionalData['yesterday'] . '\' ORDER BY delta ASC LIMIT 15 ';

        $this->modelData['loserData'] = $this->db->rawQuery($query);

    }

    public function getChances()
    {

        $query   = [];
        $query[] = 'SELECT DISTINCT r.keywordID, k.keywordName, r.rankingPosition, r.rankingURL';
        $query[] = ' FROM st_rankings r JOIN st_keywords k ON r.keywordID=k.keywordID WHERE r.projectID=' . $this->projectData['currentProjectID'] . ' AND r.rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND r.rankingPosition BETWEEN 4 AND 25 ORDER BY r.rankingPosition ASC LIMIT 15';

        $query = implode(' ', $query);

        $this->modelData['chancesData'] = $this->db->rawQuery($query);

    }

    public function getQuickInfoData()
    {
        $query   = [];
        $query[] = 'SELECT ';

        $query[] = '(SELECT COUNT(*) FROM st_keywords WHERE parentProjectID=' . $this->projectData['currentProjectParentID'] . ' ) as keywords,';
        $query[] = '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectParentID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\') as keywordsTrackedToday,';
        $query[] = '(SELECT COUNT(*) FROM st_projects WHERE parentProjectID=' . $this->projectData['currentProjectParentID'] . ' ) as competition,';
        $query[] = '(SELECT iFNull(ROUND(AVG(ifNull(rankingPosition,100)),2),0) as durchschnittHeute FROM st_rankings WHERE projectID = ' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\') as rankIndexToday';

        $query = implode(' ', $query);

        $this->modelData['quickInfoData'] = $this->db->rawQuery($query);

    }

    public function setProjectData()
    {

        $projectIDinSession = $this->session->get('currentProject');

        $this->db->where('projectID', $projectIDinSession);
        $currentProjectData = $this->db->getOne('projects');

        if($this->db->count == 1) {

            $this->saveProjectData($currentProjectData);
        }
        else {

            $this->db->where('projectDefault', 1);
            $alternativeProjectData = $this->db->getOne('projects');

            if($this->db->count == 1) {
                $this->saveProjectData($this->db->getOne('projects'));
            }
            else {
                $this->db->orderBy('projectID', 'ASC');
                $this->saveProjectData($this->db->getOne('projects'));
            }
        }

        $this->saveCompetition();
        $this->modelData['projectData'] = $this->projectData;

    }

    private function saveProjectData($dataSrc)
    {

        $this->projectData['currentProjectID']       = intval($dataSrc['projectID']);
        $this->projectData['currentProjectURL']      = $dataSrc['projectURL'];
        $this->projectData['currentProjectParentID'] = intval($dataSrc['parentProjectID']);
        $this->session->set('currentProject', intval($dataSrc['projectID']));

    }

    private function saveCompetition()
    {
        $this->db->where('parentProjectID', $this->projectData['currentProjectParentID']);
        $this->db->orderBy('projectID', 'ASC');
        $this->projectData['competitorList'] = $this->db->get('projects', NULL, 'projectID,projectURL');

    }

    public function generatePositionDistributionQuery()
    {
        $query = 'SELECT '
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 1 AND 5)  as p1,'
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 6 AND 10)  as p2,'
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 11 AND 20)  as p3,'
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 21 AND 35)  as p4,'
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 35 AND 50)  as p5,'
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 51 AND 75)  as p6,'
                . '(SELECT COUNT(*) FROM st_rankings WHERE projectID=' . $this->projectData['currentProjectID'] . ' AND rankingAddedDay=\'' . $this->additionalData['currentDate'] . '\' AND rankingPosition BETWEEN 76 AND 100)  as p7,'
                . '(\'1 - 5\') as n1,'
                . '(\'6 - 10\') as n2,'
                . '(\'11 - 20\') as n3,'
                . '(\'21 - 35\') as n4,'
                . '(\'36 - 50\') as n5,'
                . '(\'51 - 75\') as n6,'
                . '(\'76 - 100\') as n7';

        $this->query = $query;

    }

    public function sendQueryToDB()
    {

        $this->modelData['queryResult'] = $this->db->rawQuery($this->query);

    }

    public function setSelectDate($getParams)
    {
        if(isset($getParams['date'])) {
            $this->additionalData['currentDate'] = $getParams['date'];
        }
        else {
            $this->additionalData['currentDate'] = date('Y-m-j', strtotime('-0 day'));
            $this->additionalData['yesterday']   = date('Y-m-j', strtotime('-1 day'));
        }

    }

    public function setAdditionalData(array $additionalData)
    {

        foreach ($additionalData as $dataKey => $dataValue) {
            $this->modelData[$dataKey] = $dataValue;
        }

    }

    public function getModelData()
    {

        $this->setAdditionalData($this->additionalData);
        return $this->modelData;

    }

}
