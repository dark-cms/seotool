<?php

namespace App\Model;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use MysqliDb as DB;

class Projects
{

    private $request;
    private $response;
    private $db;
    private $modelData = [];
    private $additionalData;

    public function __construct(Request $request, Response $response, DB $db)
    {

        $this->request  = $request;
        $this->response = $response;
        $this->db       = $db;

        $this->additionalData['currentProjectNameData'] = new \App\CurrentProject_TopBar($this->db);

        $this->additionalData['projectListData'] = new \App\ProjectList_TopBar($this->db);

    }

    public function loadProjectsToEditData($projectID)
    {
        $this->additionalData['projectID'] = $projectID;

        $this->db->where('projectID', $projectID);
        $this->modelData['mainProjectData'] = $this->db->getOne("projects");

        $this->db->where('parentProjectID', $projectID);
        $this->db->where('projectIsParent', 0);
        $this->modelData['competetionData'] = $this->db->get("projects");

    }

    public function loadIndexPageData()
    {
        $this->additionalData['title'] = 'SEO Tool: Projektübersicht';

    }

    public function loadAddPageData()
    {
        $this->additionalData['title'] = 'SEO Tool: Projekt hinzufügen';

    }

    public function loadEditPageData()
    {
        $this->additionalData['title'] = 'SEO Tool: Projekt bearbeiten';

    }

    public function loadProjects()
    {

        $this->modelData['projects'] = $this->db->rawQuery('
                SELECT 
                    p1.projectID,
                    p1.projectURL,
                    p1.projectAdded, 
                    (
                    SELECT 
                        GROUP_CONCAT(p2.projectURL SEPARATOR \', \') 
                    FROM 
                        st_projects p2 
                    WHERE 
                        p2.parentProjectID = p1.projectID
                    AND
                        p2.projectIsParent = 0
                    ) as projectCompetition,
                    (
                    SELECT 
                        COUNT(p3.keywordID) 
                    FROM 
                        st_keywords p3 
                    WHERE 
                        p3.parentProjectID=p1.projectID
                    ) as projectsKeywordCount
                FROM 
                    st_projects p1 
                WHERE 
                    p1.projectIsParent = 1 
                ORDER BY
                    p1.projectID ASC
                '
        );

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
